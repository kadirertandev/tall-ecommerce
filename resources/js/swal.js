let defaultOptions = {
  titleText: "",
  text: "",
  position: "center",
  icon: "success",
  iconHtml: ``,
  customClass: {
    container: "",
    popup: "",
    icon: "",
    title: '', //titleText
    htmlContainer: '', //text
  },
  toast: false,
  animation: true,

  showCloseButton: false,
  closeButtonHtml: `&times;`,

  showConfirmButton: false,
  showDenyButton: false,
  showCancelButton: false,

  confirmButtonText: 'Yes',
  denyButtonText: "No",
  cancelButtonText: "Cancel",

  confirmButtonColor: "#60c733",
  denyButtonColor: "#ef4444",
  cancelButtonColor: "#7a7a7a",

  allowOutsideClick: true,

  timer: 1000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
};

window.addEventListener("swal-fire", function (event) {
  let eventDetails = event.detail;

  let options = { ...defaultOptions, ...eventDetails }

  console.log("default options:\n", defaultOptions)
  console.log("options:\n", options)

  Swal.fire({
    ...options
  }).then(result => {
    if (options.icon === "question") {
      console.log(result)
      switch (true) {
        case result.isConfirmed:
          console.log("confirmed")
          Livewire.dispatch(options.onConfirm, { ...options.onConfirmParameters })
          break;
        case result.isDenied:
          console.log("denied")
          Livewire.dispatch(options.onDeny, { ...options.onDenyParameters })
          break;
        case result.isDismissed:
          console.log("dismissed")
          Livewire.dispatch(options.onDismiss, { ...options.onDismissParameters })
          break;
        default:
          console.log("error");
      }
    }
  })

  console.log("swal fired")

});
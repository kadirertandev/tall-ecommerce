import './bootstrap';

Livewire.on('livewire:initialized', () => {
  initFlowbite();
});

window.addEventListener('livewire:navigated', () => {
  initFlowbite();
});



//Cart
window.addEventListener("added-to-cart", function (event) {
  let product = event.detail.product;
  console.log("added to cart...");
  console.log(product);

  Swal.fire({
    title: product.name,
    text: event.detail.text,
    icon: "success",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke- width="1.5" stroke = "currentColor" class= "w-16 h-16" > <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /> </svg >`,
    timer: 800,
    position: "top-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("remove-from-cart-modal", function (event) {
  console.log(event.detail.cartItemId);
  Swal.fire({
    title: "Bu ürünü sepetinizden çıkartmak istediğinizden emin misiniz?",
    position: "top-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Sil ve Favorilere ekle",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("remove-form-cart-modal-is-confirmed", { cartItemId: event.detail.cartItemId, addFavorites: false })
        break;
      case result.isDenied:
        // alert("remove and add to favorites")
        Livewire.dispatch("remove-form-cart-modal-is-denied", { cartItemId: event.detail.cartItemId, addFavorites: true })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("removed-from-cart", function (event) {
  let product = event.detail.product;
  console.log("added to cart...");
  console.log(product);

  Swal.fire({
    title: product.name,
    text: event.detail.text,
    icon: "success",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke- width="1.5" stroke = "currentColor" class= "w-16 h-16" > <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /> </svg >`,
    timer: 800,
    position: "top-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("removed-from-cart-and-added-favorites", function (event) {
  let product = event.detail.product;
  console.log("added to cart...");
  console.log(product);

  Swal.fire({
    title: product.name,
    text: event.detail.text,
    icon: "success",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke- width="1.5" stroke = "currentColor" class= "w-16 h-16" > <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /> </svg >`,
    timer: 1200,
    position: "top-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})

//Favorites
window.addEventListener("add-to-favorites", function (event) {
  console.log(event)
  Swal.fire({
    title: event.detail.text,
    icon: "success",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-16 h-16"> <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>`,
    timer: 800,
    position: "bottom-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("add-to-favorites-error", function (event) {
  Swal.fire({
    title: event.detail.text,
    icon: "error",
    // iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-16 h-16"> <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>`,
    timer: 800,
    position: "bottom-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("add-to-favorites-guest-error", function (event) {
  Swal.fire({
    title: "Please log in.",
    text: "You can add product to your favorites after logging in.",
    icon: "error",
    timer: 1700,
    position: "center",
    backdrop: true,
    timerProgressBar: true,
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })

})
window.addEventListener("remove-from-favorites", function (event) {
  console.log(event.detail.product);
  console.log(event.detail.text);

  Swal.fire({
    title: event.detail.text,
    icon: "success",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="#ff0000" d="M3.28 2.22a.75.75 0 1 0-1.06 1.06l1.855 1.856a5.375 5.375 0 0 0-.5 8.044l7.895 7.896a.75.75 0 0 0 1.06 0l3.744-3.742l4.445 4.447a.75.75 0 0 0 1.061-1.061zm17.152 10.959l-2.036 2.035L7.19 4.008a5.36 5.36 0 0 1 3.986 1.57l.823.824l.82-.822a5.38 5.38 0 0 1 7.613 7.599"/></svg>`,
    timer: 800,
    position: "bottom-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("remove-from-favorites-error", function (event) {
  /* console.log(event.detail);
  return; */
  Swal.fire({
    title: event.detail.text,
    icon: "error",
    // iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="red" viewBox="0 0 256 256"><path d="M178,40a61.6,61.6,0,0,0-43.84,18.16L128,64.32l-6.16-6.16A62,62,0,0,0,16,102c0,70,103.79,126.67,108.21,129a8,8,0,0,0,7.58,0C136.21,228.67,240,172,240,102A62.07,62.07,0,0,0,178,40ZM128,214.8C109.74,204.16,32,155.69,32,102a46,46,0,0,1,78.53-32.53l6.16,6.16L106.34,86a8,8,0,0,0,0,11.31l24.53,24.53-16.53,16.52a8,8,0,0,0,11.32,11.32l22.18-22.19a8,8,0,0,0,0-11.31L123.31,91.63l22.16-22.16A46,46,0,0,1,224,102C224,155.61,146.24,204.15,128,214.8Z"></path></svg>`,
    // iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="red" viewBox="0 0 256 256"><path d="M223,57a58.1,58.1,0,0,0-82-.06L128,69.47,115,56.91a58,58,0,0,0-82,82.05l89.37,90.66a8,8,0,0,0,11.4,0L223,139A58.09,58.09,0,0,0,223,57Zm-11.36,70.76L128,212.6,44.29,127.68a42,42,0,1,1,59.41-59.4l.1.1,12.67,12.19-10,9.65a8,8,0,0,0-.11,11.42L132.69,128l-10.35,10.35a8,8,0,0,0,11.32,11.32l16-16a8,8,0,0,0,0-11.31L123.42,96.09,152.2,68.38l.11-.1a42,42,0,1,1,59.37,59.44Z"></path></svg>`,
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="#ff0000" d="M3.28 2.22a.75.75 0 1 0-1.06 1.06l1.855 1.856a5.375 5.375 0 0 0-.5 8.044l7.895 7.896a.75.75 0 0 0 1.06 0l3.744-3.742l4.445 4.447a.75.75 0 0 0 1.061-1.061zm17.152 10.959l-2.036 2.035L7.19 4.008a5.36 5.36 0 0 1 3.986 1.57l.823.824l.82-.822a5.38 5.38 0 0 1 7.613 7.599"/></svg>`,
    timer: 800,
    position: "bottom-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      title: "text-nowrap"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})

//Comment
window.addEventListener("comment-success", function (event) {
  clearRate()
})
window.addEventListener("comment-error", function (event) {
  Swal.fire({
    title: event.detail.title ?? "Please log in.",
    text: event.detail.text ?? "You can evaluate the product after logging in.",
    icon: "error",
    timer: 1500,
    position: "center",
    backdrop: true,
    timerProgressBar: true,
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

//User
window.addEventListener("user-profile-update", function (event) {
  Swal.fire({
    title: "Profile Updated",
    header: "hi",
    iconHtml: `<svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-16 h-16"> <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>`,
    timer: 800,
    position: "center",
    backdrop: true,
    /* toast: true,
    target: "aboutus", */
    /* color: "orange",
    background: "black", */
    timerProgressBar: true,
    customClass: {
      container: 'bg-blue-500',
      popup: 'bg-red-500',
      header: 'bg-teal-500',
      title: 'bg-teal-500',
      closeButton: '...',
      icon: '...',
      image: '...',
      htmlContainer: '...',
      input: '...',
      inputLabel: '...',
      validationMessage: '...',
      actions: '...',
      confirmButton: '...',
      denyButton: '...',
      cancelButton: '...',
      loader: '...',
      footer: '....',
      timerProgressBar: '....',
    },
    showConfirmButton: true,
    showCancelButton: true,
    width: "",
  })
})
window.addEventListener("delete-address-modal", function (event) {
  console.log(event.detail.addressId);
  Swal.fire({
    title: "Bu adresi silmek istediğinizden emin misiniz?",
    position: "center",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      title: "text-xl text-nowrap",
    },
    showConfirmButton: true,
    showDenyButton: true,
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("delete-address-modal-is-confirmed", { addressId: event.detail.addressId })
        break;
      case result.isDenied:
        // alert("remove and add to favorites")
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("reset-password-mail-sent", function (event) {
  Swal.fire({
    title: "Check Your Indox",
    text: "We have e-mailed your password reset link!",
    icon: "success",
    position: "center",
    backdrop: true,
    showConfirmButton: true,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("reset-password-success", function (event) {
  Swal.fire({
    title: "Password Reset Successful!",
    icon: "success",
    timer: "1500",
    position: "center",
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("change-password-success", function (event) {
  Swal.fire({
    title: "Change Password Successful!",
    icon: "success",
    timer: "1500",
    position: "center",
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

//Admin
window.addEventListener("create_product_success", function (event) {
  Swal.fire({
    title: "Product created successfully!",
    // text: "We have e-mailed your password reset link!",
    icon: "success",
    position: "top-right",
    timer: 1500,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("update_product_success", function (event) {
  Swal.fire({
    title: "Product updated successfully!",
    // text: "We have e-mailed your password reset link!",
    icon: "success",
    position: "top-right",
    timer: 1500,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete-product-modal", function (event) {
  console.log(event.detail.productId);
  Swal.fire({
    title: "Bu ürünü silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("delete-product-modal-is-confirmed", { productId: event.detail.productId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("delete_product_success", function (event) {
  Swal.fire({
    title: "Product deleted successfully!",
    // text: "We have e-mailed your password reset link!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete_product_error", function (event) {
  Swal.fire({
    title: event.detail.title,
    text: event.detail.text,
    icon: "error",
    position: "center",
    backdrop: false,
    showConfirmButton: true,
    confirmButtonColor: "#0694a2",
    confirmButtonText: "OKAY",
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
      popup: "min-w-[1100px]",
    }
  })
})
window.addEventListener("force-delete-product-modal", function (event) {
  console.log(event.detail.productId);
  Swal.fire({
    title: "Bu ürünü kalıcı olarak silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("force-delete-product-modal-is-confirmed", { productId: event.detail.productId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("force-delete_product_success", function (event) {
  Swal.fire({
    title: "Product deleted permanently successfully!",
    // text: "We have e-mailed your password reset link!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

window.addEventListener("create_category_success", function (event) {
  Swal.fire({
    title: "Category created successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("update_category_success", function (event) {
  Swal.fire({
    title: "Category updated successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete-category-modal", function (event) {
  console.log(event.detail.categoryId);
  Swal.fire({
    title: "Bu kategoriyi silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("delete-category-modal-is-confirmed", { categoryId: event.detail.categoryId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("delete_category_success", function (event) {
  Swal.fire({
    title: "Category deleted successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete_category_error", function (event) {
  Swal.fire({
    title: event.detail.title,
    text: event.detail.text,
    icon: "error",
    position: "center",
    backdrop: false,
    showConfirmButton: true,
    confirmButtonColor: "#0694a2",
    confirmButtonText: "OKAY",
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
      popup: "min-w-[900px]",
    }
  })
})
window.addEventListener("force-delete-category-modal", function (event) {
  console.log(event.detail.categoryId);
  Swal.fire({
    title: "Bu ürünü kalıcı olarak silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("force-delete-category-modal-is-confirmed", { categoryId: event.detail.categoryId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("force-delete_category_success", function (event) {
  Swal.fire({
    title: "Category deleted permanently successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

window.addEventListener("create_brand_success", function (event) {
  Swal.fire({
    title: "Brand created successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("update_brand_success", function (event) {
  Swal.fire({
    title: "Brand updated successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete-brand-modal", function (event) {
  console.log(event.detail.brandId);
  Swal.fire({
    title: "Bu markayı silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("delete-brand-modal-is-confirmed", { brandId: event.detail.brandId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("delete_brand_success", function (event) {
  Swal.fire({
    title: "Brand deleted successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete_brand_error", function (event) {
  Swal.fire({
    title: event.detail.title,
    text: event.detail.text,
    icon: "error",
    position: "center",
    backdrop: false,
    showConfirmButton: true,
    confirmButtonColor: "#0694a2",
    confirmButtonText: "OKAY",
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
      popup: "min-w-[900px]",
    }
  })
})
window.addEventListener("force-delete-brand-modal", function (event) {
  console.log(event.detail.brandId);
  Swal.fire({
    title: "Bu markayı kalıcı olarak silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("force-delete-brand-modal-is-confirmed", { brandId: event.detail.brandId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("force-delete_brand_success", function (event) {
  Swal.fire({
    title: "Brand deleted permanently successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

window.addEventListener("create_admin_success", function (event) {
  Swal.fire({
    title: "Admin created successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("update_admin_success", function (event) {
  Swal.fire({
    title: "Admin updated successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete-admin-modal", function (event) {
  console.log(event.detail.adminId);
  Swal.fire({
    title: "Bu yetkiliyi silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        Livewire.dispatch("delete-admin-modal-is-confirmed", { adminId: event.detail.adminId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("delete_admin_success", function (event) {
  Swal.fire({
    title: "Admin deleted successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("force-delete-admin-modal", function (event) {
  console.log(event.detail.adminId);
  Swal.fire({
    title: "Bu yetkiliyi kalıcı olarak silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("force-delete-admin-modal-is-confirmed", { adminId: event.detail.adminId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("force-delete_admin_success", function (event) {
  Swal.fire({
    title: "Admin deleted permanently successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

window.addEventListener("force_delete_customer_success", function (event) {
  Swal.fire({
    title: "Customer deleted permanently successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

window.addEventListener("update_review_success", function (event) {
  Swal.fire({
    title: "Review updated successfully!",
    icon: "success",
    position: "top-right",
    timer: 1100,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("delete-review-modal", function (event) {
  console.log(event.detail.reviewId);
  Swal.fire({
    title: "Bu incelemeyi silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        Livewire.dispatch("delete-review-modal-is-confirmed", { reviewId: event.detail.reviewId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("delete_review_success", function (event) {
  Swal.fire({
    title: "Review deleted successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})
window.addEventListener("force-delete-review-modal", function (event) {
  console.log(event.detail.reviewId);
  Swal.fire({
    title: "Bu incelemeyi kalıcı olarak silmek istediğinizden emin misiniz?",
    position: "center-end",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max",
      container: "z-[8889]",
      title: "text-xl",
    },
    showConfirmButton: true,
    showDenyButton: true,
    confirmButtonText: 'Evet',
    denyButtonText: "Hayır",
    confirmButtonColor: "#dc3545",
    denyButtonColor: "#14b7c4",
    width: "",
  }).then(result => {
    console.log(result)
    switch (true) {
      case result.isConfirmed:
        // alert("remove from cart")
        Livewire.dispatch("force-delete-review-modal-is-confirmed", { reviewId: event.detail.reviewId })
        break;
      default:
      // alert("error");
    }
  })

})
window.addEventListener("force-delete-review-success", function (event) {
  Swal.fire({
    title: "Review deleted permanently successfully!",
    icon: "success",
    position: "top-right",
    timer: 700,
    backdrop: true,
    showConfirmButton: false,
    showCancelButton: false,
    customClass: {
      title: "text-nowrap",
      text: "text-nowrap",
    }
  })
})

//Errors
window.addEventListener("unauthorized-action", function (event) {
  console.log(event);
  Swal.fire({
    title: "THIS ACTION IS UNAUTHORIZED!",
    icon: "error",
    timer: 1000,
    position: "center",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	",
      title: "text-nowrap"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("something-went-wrong", function (event) {
  console.log(event.detail);
  // return;
  Swal.fire({
    title: "Something went wrong!",
    icon: "error",
    timer: 800,
    position: "center",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})
window.addEventListener("error-with-message", function (event) {
  Swal.fire({
    title: event.detail.message,
    icon: "error",
    timer: event.detail.timer ?? 800,
    position: "center",
    customClass: {
      icon: 'border-0',
      popup: "mt-20 max-w-max	"
    },
    showConfirmButton: false,
    showCancelButton: false,
    width: "",
  })

})

/*
customClass: {
  container: '...',
  popup: '...',
  header: '...',
  title: '...',
  closeButton: '...',
  icon: '...',
  image: '...',
  input: '...',
  inputLabel: '...',
  validationMessage: '...',
  actions: '...',
  confirmButton: '...',
  denyButton: '...',
  cancelButton: '...',
  loader: '...',
  footer: '....',
  timerProgressBar: '....',
}
*/
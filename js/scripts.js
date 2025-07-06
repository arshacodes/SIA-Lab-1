$(document).ready(function (){
    $('#signup-customer-form').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: 'functions/signup_customer.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                Swal.fire({
                    title: response.error ? 'Error' : 'Success',
                    text: response.message,
                    icon: response.error ? 'error' : 'success'
                }).then(() => {
                    if (!response.error) {
                        window.location.href = 'index.php';
                    }
                });
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'AJAX request failed', 'error');
            }
        });
    });

    $('#customer-login-form').on('submit', function (e) {
        e.preventDefault();
        $.post('functions/customer_login.php', $(this).serialize(), r => {
            if (r.error) {
                Swal.fire('Error', r.message, 'error');
            } else {
                window.location.href = 'index.php';
            }
        }, 'json');
    });

    $("#search-shop-box").on("keyup", function () {
        let value = $(this).val().toLowerCase();

        $(".product-item").each(function () {
            let productName = $(this).find(".product-name").text().toLowerCase();

            $(this).toggle(productName.includes(value));
        });
    });

    $("#search-shop-box, #category-filter").on("input change", function () {
        let searchValue = $("#search-shop-box").val().toLowerCase();
        let selectedCategory = $("#category-filter").val();

        $(".product-item").each(function () {
            let productName = $(this).find(".product-name").text().toLowerCase();
            let productCategory = $(this).data("category");

            let matchesSearch = productName.includes(searchValue);
            let matchesCategory = selectedCategory === "" || productCategory == selectedCategory;

            $(this).toggle(matchesSearch && matchesCategory);
        });
    });

    $("#add_to_cart").on("submit", function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "functions/add_to_cart.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    title: response.error ? "Error" : "Success",
                    text: response.message,
                    icon: response.error ? "error" : "success"
                }).then(() => {
                    if(response.error){
                        window.location.href = "login.php";
                    }else{
                        location.reload();
                    }
                });
            },
            error: function (response) {
                Swal.fire("Error!", "Failed to add item to cart. Try again.", "error");
            }
        });
    });

    // $(".edit-item-btn").on("click", function () {
    //     $("#edit-cart-item-id").val($(this).data("id"));
    //     $("#edit-product-id").val($(this).data("product-id"));

    //     $("#editItemModal").modal("show");
    //     console.log("EDIT DAW");
    // });

    // $("#edit-item-form").on("submit", function (e) {
    //     e.preventDefault();
    //     let formData = $(this).serialize();

    //     $.ajax({
    //         url: "functions/update_cart.php",
    //         type: "POST",
    //         data: formData,
    //         dataType: "json",
    //         success: function (response) {
    //             Swal.fire({
    //                 title: response.error ? "Error" : "Success",
    //                 text: response.message,
    //                 icon: response.error ? "error" : "success"
    //             }).then(() => {
    //                 if (!response.error) {
    //                     location.reload();
    //                 }
    //             });
    //         },
    //         error: function () {
    //             Swal.fire("Error!", "Failed to update item. Try again.", "error");
    //         }
    //     });
    // });

    // $(".delete-item-btn").on("click", function () {
    //     let cartItemId = $(this).data("id");

    //     Swal.fire({
    //         title: "Confirm Deletion",
    //         text: "Are you sure you want to remove this item from the cart?",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#d33",
    //         confirmButtonText: "Yes, delete it!"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: "functions/delete_cart.php",
    //                 type: "POST",
    //                 data: { cart_item_id: cartItemId },
    //                 dataType: "json",
    //                 success: function (response) {
    //                     Swal.fire({
    //                         title: response.error ? "Error" : "Deleted",
    //                         text: response.message,
    //                         icon: response.error ? "error" : "success"
    //                     }).then(() => {
    //                         if (!response.error) {
    //                             location.reload();
    //                         }
    //                     });
    //                 },
    //                 error: function () {
    //                     Swal.fire("Error!", "Failed to remove item. Try again.", "error");
    //                 }
    //             });
    //         }
    //     });
    // });

    // $(".cart-checkbox").on("change", function(e) {  
    //     let customer_id = $(this).data("customer-id");
    //     let cart_item_id = $(this).data("id");
    //     let cart_item_price = parseFloat($(this).data("total-price"));

    //     if (selected_cart_items.includes(cart_item_id)) {
    //         let index = selected_cart_items.indexOf(cart_item_id);
    //         selected_cart_items.splice(index, 1);
    //         cart_total_price -= cart_item_price;
    //         $(".checkout-item[data-id='" + cart_item_id + "']").fadeToggle();
    //     } else {
    //         selected_cart_items.push(cart_item_id);
    //         cart_total_price += cart_item_price;
    //         $(".checkout-item[data-id='" + cart_item_id + "']").fadeToggle();
    //     }

    //     updateCheckout();
    // });

    // $("#checkout-form").on("submit", function (e) {
    //     e.preventDefault();

    //     if (selected_cart_items.length == 0) {
    //         Swal.fire("Warning!", "Please select items for checkout.", "warning");
    //         return;
    //     }
    //     document.getElementById("checkoutPreviewModal").removeAttribute("inert");
    //     $("#checkoutPreviewModal").modal("show");
    // });

    // $("#checkout-preview-form").on("submit", function (e) {
    //     e.preventDefault();

    //     let checkoutData = {
    //         selected_items: selected_cart_items,
    //         price_total: cart_total_price,
    //         courier_service_id: $("#courier-service").val(),
    //         address: $("#address").val(),
    //         comments: $("#comments").val(),
    //     };

    //     $("#modal-courier-service").text($("#courier-service option:selected").text());
    //     $("#modal-address").text(checkoutData.address || "Not provided");
    //     $("#modal-comments").text(checkoutData.comments || "No comments");

    //     $.ajax({
    //         url: "functions/checkout.php",
    //         type: "POST",
    //         contentType: "application/json",
    //         data: JSON.stringify(checkoutData),
    //         success: function (response) {
    //             Swal.fire({
    //                 title: response.error ? "Error" : "Success",
    //                 text: response.message,
    //                 icon: response.error ? "error" : "success"
    //             }).then(() => {
    //                 if (!response.error) {
    //                     window.location.href = "cart.php";
    //                 }
    //             });
    //         },
    //         error: function () {
    //             Swal.fire("Oops!", "Server error. Please try again.", "error");
    //         }
    //     });
    // });

    $("#update-account-form").on("submit", function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "functions/update_account.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    title: response.error ? "Error" : "Success",
                    text: response.message,
                    icon: response.error ? "error" : "success"
                }).then(() => {
                    if (!response.error) {
                        location.reload();
                    }
                });
            },
            error: function () {
                Swal.fire("Error!", "Failed to update account. Try again.", "error");
            }
        });
    });

    $("#logout-btn").on("click", function () {
        $.ajax({
            url: "functions/logout.php",
            type: "POST",
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    title: "Logged Out!",
                    text: response.message,
                    icon: "success"
                }).then(() => {
                    window.location.href = "login.php";
                });
            },
            error: function (xhr, status, error) {
                Swal.fire("Error!", "Logout was successful but an error occurred in AJAX.", "error")
                    .then(() => {
                        window.location.href = "login.php";
                    });
            }
        });
    });

    $("#change-password-btn").on("click", function () {
        $("#changePasswordModal").modal("show");
    });

    $("#change-password-form").on("submit", function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "functions/change_password.php",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (response) {
                Swal.fire({
                    title: response.error ? "Error" : "Success",
                    text: response.message,
                    icon: response.error ? "error" : "success"
                }).then(() => {
                    if (!response.error) {
                        $("#changePasswordModal").modal("hide");
                    }
                });
            },
            error: function () {
                Swal.fire("Error!", "Failed to update password. Try again.", "error");
            }
        });
    });

    $("#delete-customer-btn").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "This will permanently delete your account.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "functions/delete_account.php",
                    type: "POST",
                    success: function (response) {
                        Swal.fire({ title: "Success!", text: 'Account deleted successfully', icon: "success" }).then(() => location.reload());
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({ title: "Error", text: "Something went wrong. Please try again.", icon: "error" });
                    }
                });
            }
        });
    });
})
$(document).ready(function () {
    console.log("this is cart.js");

    const selected_cart_items = [];
    let cart_total_price = 0.00;


    function updateCheckout() {
        document.getElementById("selected-count").innerText = selected_cart_items.length;

        const options = {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        };

        formatted_cart_total_price = cart_total_price.toLocaleString('en-US', options);
        document.getElementById("item-total-price").innerText = formatted_cart_total_price;
        document.getElementById("modal-total-price").innerText = formatted_cart_total_price;
    }

    $(".edit-item-btn").on("click", function () {
        $("#edit-cart-item-id").val($(this).data("id"));
        $("#edit-product-id").val($(this).data("product-id"));

        $("#editItemModal").modal("show");
    });

    $("#edit-item-form").on("submit", function (e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: "functions/update_cart.php",
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
                Swal.fire("Error!", "Failed to update item. Try again.", "error");
                $("#editItemModal").modal("hide");
            }
        });
    });

    $(".delete-item-btn").on("click", function () {
        let cartItemId = $(this).data("id");

        Swal.fire({
            title: "Confirm Deletion",
            text: "Are you sure you want to remove this item from the cart?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "functions/delete_cart.php",
                    type: "POST",
                    data: { cart_item_id: cartItemId },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            title: response.error ? "Error" : "Deleted",
                            text: response.message,
                            icon: response.error ? "error" : "success"
                        }).then(() => {
                            if (!response.error) {
                                location.reload();
                            }
                        });
                    },
                    error: function () {
                        Swal.fire("Error!", "Failed to remove item. Try again.", "error");
                    }
                });
            }
        });
    });

    $(".cart-checkbox").on("change", function(e) {  
        let customer_id = $(this).data("customer-id");
        let cart_item_id = $(this).data("id");
        let cart_item_price = parseFloat($(this).data("total-price"));

        if (selected_cart_items.includes(cart_item_id)) {
            let index = selected_cart_items.indexOf(cart_item_id);
            selected_cart_items.splice(index, 1);
            cart_total_price -= cart_item_price;
            $(".checkout-item[data-id='" + cart_item_id + "']").fadeToggle();
        } else {
            selected_cart_items.push(cart_item_id);
            cart_total_price += cart_item_price;
            $(".checkout-item[data-id='" + cart_item_id + "']").fadeToggle();
        }

        updateCheckout();
    });

    $("#checkout-form").on("submit", function (e) {
        e.preventDefault();

        if (selected_cart_items.length == 0) {
            Swal.fire("Warning!", "Please select items for checkout.", "warning");
            return;
        }
        document.getElementById("checkoutPreviewModal").removeAttribute("inert");
        $("#checkoutPreviewModal").modal("show");
    });

    $("#checkout-preview-form").on("submit", function (e) {
        e.preventDefault();

        let checkoutData = {
            selected_items: selected_cart_items,
            total_amount: cart_total_price,
            courier_service: $("#courier-service").val(),
            comments: $("#comments").val(),
        };

        $.ajax({
            url: "functions/checkout.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(checkoutData),
            success: function (response) {
                Swal.fire({
                    title: response.error ? "Error" : "Success",
                    text: response.message,
                    icon: response.error ? "error" : "success"
                }).then(() => {
                    if (!response.error) {
                        window.location.href = "cart.php";
                    }
                });
            },
            error: function () {
                Swal.fire("Oops!", "Server error. Please try again.", "error");
            }
        });
    });
})
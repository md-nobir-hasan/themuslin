<script>
    $(document).on("change", ".shipping-methods-radio", function (){
        $("#shipping_charge").html("{{ site_currency_symbol() }}" + $(this).val());
        $("#shipping_charge").attr("data-taxable", $(this).attr("data-taxable"));

        calculateTotal();
    });

    $('.discount-coupon').on('submit', function(e) {
        e.preventDefault();

        let url = $(this).attr('action');
        let coupon = $(this).find('input[name=coupon]').val();


        $('.lds-ellipsis').show();
        // for shipping code
        $('#coupon_code').val(coupon);
        $('#discount_summery').hide();
        $('#discount_summery #coupon_amount').text(amount_with_currency_symbol(0));

        submitCoupon(url, coupon);
    });


    class ShippingMethods{
        constructor(methods) {
            this.method = methods;
            this.label = "";
        }

        createHTMLBlock() {
            this.method?.forEach((method) => {

                if (method !== undefined) {
                    this.label += `<label class="order-shipping-methods-item">
                        <div class="order-shipping-methods-item-left">
                            <div class="order-shipping-methods-item-left-item">
                                <input data-taxable="${ method?.tax_status }" value='${ method.cost }' ${ method?.is_default == 1 ? "checked" : "" } type="radio" name="shipping-methods" class="shipping-methods-radio">
                                <div class="shipping-methods-radio-para text-start">
                                    <p class='title'>${method.title}</p>
                                    <small>${method.preset_name}</small>
                                </div>
                            </div>
                        </div>
                        <div class="order-shipping-methods-item-right">
                            <h6 class="order-shipping-methods-price">${"{{ site_currency_symbol() }}" +  method.cost}</h6>
                        </div>
                    </label>`;
                }

                if(method?.is_default == 1){
                    $("#shipping_charge").attr("data-taxable", method?.tax_status);

                    calculateTotal();
                }
            })

            return this.label;
        }
    }


    function submitCoupon(url, coupon) {
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                coupon: coupon,
            },
            success: function(data) {
                $('.lds-ellipsis').hide();
                if (data.type == 'success') {
                    toastr.success('{{ __('Coupon applied') }}');
                    $('#coupon_amount').text("{{ site_currency_symbol() }}" + data.coupon_amount);
                    $('#coupon_code').val(coupon);
                    $('#discount_summery').show();
                } else {
                    toastr.error('{{ __('Coupon invalid') }}');
                }

                @if(get_static_option("tax_system") == "advance_tax_system")
                    calculateOrderSummeryForAdvanceTax();
                @else
                    calculateTotal();
                    calculateOrderSummary();
                @endif

            },
            error: function(err) {
                $('.lds-ellipsis').hide();
                toastr.error('{{ __('Something went wrong') }}');
            }
        });
    }

    function calculateTotal() {
        let site_currency_symbol = "{{ site_currency_symbol() }}";
        let subtotal = Number($('#subtotal').text().trim().replace(site_currency_symbol, ''));
        let shipping_charge = Number($('#shipping_charge').text().trim().replace(site_currency_symbol, ''));
        let coupon_amount = Number($('#coupon_amount').text().trim().replace(site_currency_symbol, '').replace('(-)', ''));
        // calculate like this (subtotal + shipping_charge) - coupon_amount

        let total = 0;
        // check shipping charge is taxable or not
        if($('#shipping_charge').attr("data-taxable") != 0){
            total = subtotal + shipping_charge - coupon_amount;
        }else{
            total = subtotal - coupon_amount;
        }

        let tax_amount = total * Number($('#tax_amount').text().trim().replace("%", '')) / 100;

        $('input[name=tax_amount]').val(tax_amount + "%");
        $('#total_amount').text(site_currency_symbol + total.toFixed(0));
        $('#grand_amount').text(site_currency_symbol + (total + tax_amount).toFixed(0));
    }
</script>
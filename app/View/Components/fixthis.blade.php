Tolong perbaiki kode ini, jika ada promo bundling maka add quantity harus berbeda dengan yang tidak promo bundling,
karena jika saya add .increase quantity promo bundling itu ikut bertambah, jadi saya tidak mau hal itu terjadi :
@if ($isBundling)
    <span style="background: #bb0239;" class="badge">Bundling</span>
    <p style="margin-bottom: 0;" class="item-name">
        {{ $cart['bundling_name'] }}</p>

    <!-- Product Price and Quantity -->
    <div class="flex-content" style="display: flex; justify-content: space-between;">
        <p class="item-price">
            {{ 'Rp.' . number_format($cart['price']) }}
        </p>
    </div>

    <div class="detail-product">
        @foreach ($product_bundling_detail as $promo_products)
            @if ($cart['bundling'] == $promo_products->bundling_code)
                <ul>
                    <input name="product[]" type="hidden" value="{{ $promo_products->product_code }}">
                    <input class="add_qty_bundling" name="quantity_per_product[]"
                        value="{{ $promo_products->quantity }}" data-base-qty="{{ $promo_products->quantity }}">
                    <input type="text" name="bundling_code[]" value="{{ $promo_products->bundling_code }}">
                    <input hidden type="text" name="product_price[]" value="{{ $promo_products->product_price }}">
                </ul>
            @endif
        @endforeach
        <input name="bundling" type="text" value="{{ $cart['bundling'] }}" hidden>
    </div>

    <div style="display: flex; gap:10px;" class="btn-delete-product">

        <button type="button" class="text-danger" style="background:none;border:none;"
            onclick="event.preventDefault();document.getElementById('delete-{{ $code }}').submit();">
            <i class="fa fa-trash"></i>
        </button>

        <div class="product-item" data-stock="{{ $cart['stock_available'] }}">
            <div style="display: none;" class="stok">
                <p>Stok:
                    <span class="available-stock">{{ $cart['stock_available'] }}</span>
                </p>
            </div>
            <!-- Quantity Control -->
            <small class="error-msg" style="color:red; display:none;font-size: 12px;"></small>
            <div class="quantity-container">
                <button type="button" class="decrease-bundle">-</button>
                <input name="quantity_per_product[]" value="1" min="1" type="number" class="item-quantity">
                <button type="button" class="increase-bundle">+</button>
            </div>

        </div>
    </div>
@else
    <p style="margin-bottom: 0;" class="item-name">
        {{ $cart['product_name'] }}</p>
    <input name="product[]" type="hidden" value="{{ $cart['product'] }}">
    <input name="variant[]" type="hidden" value="{{ $cart['variant'] }}">
    <input type="hidden" name="product_price[]" value="{{ $cart['price'] }}">

    <small class="text-info" style="margin-bottom: 0;" class="item-price">
        {{ $cart['variant_type'] }}
    </small>

    <!-- Product Price and Quantity -->
    <div class="flex-content" style="display: flex; justify-content: space-between;">
        <p class="item-price">
            {{ 'Rp.' . number_format($cart['price']) }}
        </p>
    </div>

    <div style="display: flex; gap:10px;" class="btn-delete-product">

        <button type="button" class="text-danger" style="background:none;border:none;"
            onclick="event.preventDefault();document.getElementById('delete-{{ $code }}').submit();">
            <i class="fa fa-trash"></i>
        </button>

        <div class="product-item" data-stock="{{ $cart['stock_available'] }}">
            <div style="display: none;" class="stok">
                <p>Stok:
                    <span class="available-stock">{{ $cart['stock_available'] }}</span>
                </p>
            </div>
            <!-- Quantity Control -->
            <small class="error-msg" style="color:red; display:none;font-size: 12px;"></small>
            <div class="quantity-container">
                <button type="button" class="decrease">-</button>
                <input name="quantity_per_product[]" value="1" min="1" type="number" class="item-quantity">
                <button type="button" class="increase">+</button>
            </div>

        </div>
    </div>
@endif

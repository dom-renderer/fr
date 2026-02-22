<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Invoice - #{{ $order->order_number }}</title>
    <style>
        @font-face {
            font-family: "NotoSansGujarati";
            src: url("{{ storage_path('fonts/NotoSansGujarati-Regular.ttf') }}") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        .gujarati {
            font-family: "NotoSansGujarati", "DejaVu Sans", sans-serif;
        }

        html {
            font-family: "NotoSansGujarati", "DejaVu Sans", sans-serif;
        }

        body {
            font-family: "NotoSansGujarati", "DejaVu Sans", sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 10px;
        }

        .container {
            width: 100%;
            border: 1px solid #e0e0e0;
            padding: 12px 14px;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        .left {
            float: left;
        }

        .right {
            float: right;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .small {
            font-size: 9px;
        }

        .bold {
            font-weight: bold;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-5 {
            margin-bottom: 5px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .border-top {
            border-top: 1px solid #e0e0e0;
        }

        .border-bottom {
            border-bottom: 1px solid #e0e0e0;
        }

        .box {
            border: 1px solid #e0e0e0;
            padding: 6px 8px;
            margin-bottom: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px 5px;
        }

        th {
            background-color: #f5f5f5;
            font-size: 10px;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #e0e0e0;
        }

        .label {
            font-size: 9px;
            color: #666666;
            text-transform: uppercase;
        }

        .value {
            font-size: 11px;
            color: #111111;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        <div class="clearfix border-bottom pb-5 mb-5">
            <div class="left">
                <div class="bold" style="font-size:20px;color:#b91c1c;">Farki</div>
                <div class="small" style="color:#ef4444;">હરપલ બને ઉત્સવ...</div>
                {{-- <div class="mt-10 small" style="color:#4b5563;">
                    <div class="label mb-5">From:</div>
                    <div class="bold" style="color:#374151;">
                        {{ $order->senderStore->name ?? 'Farki Central Store' }}
                    </div>
                    <div>{{ $order->senderStore->address1 ?? '' }}</div>
                    @if($order->senderStore->address2)
                        <div>{{ $order->senderStore->address2 }}</div>
                    @endif
                    <div>Phone: {{ $order->senderStore->mobile ?? '-' }}</div>
                </div> --}}
            </div>
            <div class="right text-right">
                <div class="bold" style="font-size:20px;color:#111827;">
                    @if(in_array($order->order_type, ['franchise', 'dealer']))
                        PURCHASE ORDER
                    @else
                        INVOICE
                    @endif
                </div>
                <div class="mt-10 small">
                    <div>
                        @if(in_array($order->order_type, ['franchise', 'dealer']))
                            PO No:
                        @else
                            Invoice No:
                        @endif
                        <span class="bold" style="color:#111827;">#{{ $order->order_number }}</span>
                    </div>
                    <div>Date:
                        <span class="bold" style="color:#111827;">
                            {{ $order->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Billing and Shipping --}}
        <table>
            <tr>
                <td width="50%" valign="top">
                    <div class="box">
                        <div class="label mb-5">Bill To</div>
                        @if($order->for_customer)
                            <div class="bold mt-5">{{ $order->customer_first_name }}</div>
                            <div class="small">
                                {{ $order->delivery_address }}
                            </div>
                            <div class="small mt-5 border-top pt-5">
                                Phone:
                                <span class="bold">{{ $order->customer_phone_number ?? '-' }}</span>
                            </div>
                        @elseif($order->billing_name)
                            <div class="bold mt-5">{{ $order->billing_name }}</div>
                            <div class="small">
                                {{ $order->billing_address_1 }}
                                @if($order->billing_address_2)
                                    <br>{{ $order->billing_address_2 }}
                                @endif
                                @if($order->billing_pincode)
                                    - {{ $order->billing_pincode }}
                                @endif
                            </div>
                            <div class="small mt-5 border-top pt-5">
                                Phone:
                                <span class="bold">{{ $order->billing_contact_number ?? '-' }}</span>
                            </div>
                        @else
                            @if($order->order_type === 'dealer' && $order->dealer)
                                <div class="bold">{{ $order->dealer->name }}</div>
                                <div class="small">{{ $order->dealer->address ?? '-' }}</div>
                            @elseif($order->receiverStore)
                                <div class="bold">{{ $order->receiverStore->code }} - {{ $order->receiverStore->name }}</div>
                                <div class="small">{{ $order->receiverStore->address ?? '-' }}</div>
                            @elseif($order->for_customer)
                                <div class="bold">{{ $order->customer_first_name }} {{ $order->customer_second_name }}</div>
                                <div class="small">Phone: {{ $order->customer_phone_number ?? '-' }}</div>
                            @endif
                        @endif
                    </div>
                </td>
                <td width="50%" valign="top">
                    <div class="box">
                        <div class="label mb-5">Ship To</div>
                        @php
                            $isSame = $order->bill_to_same_as_ship_to;
                        @endphp

                        @if($order->for_customer)
                            <div class="bold mt-5">{{ $order->customer_first_name }}</div>
                            <div class="small">
                                {{ $order->delivery_address }}
                            </div>
                            <div class="small mt-5 border-top pt-5">
                                Phone:
                                <span class="bold">{{ $order->customer_phone_number ?? '-' }}</span>
                            </div>
                        @elseif($isSame)
                            <div class="small" style="font-style:italic;color:#6b7280;">Same as Bill To</div>
                        @else
                            <div class="bold">{{ $order->shipping_name ?: $order->billing_name }}</div>
                            <div class="small">
                                {{ $order->shipping_address_1 ?: $order->billing_address_1 }}
                                @if($order->shipping_address_2 || $order->billing_address_2)
                                    <br>{{ $order->shipping_address_2 ?: $order->billing_address_2 }}
                                @endif
                                @if($order->shipping_pincode || $order->billing_pincode)
                                    - {{ $order->shipping_pincode ?: $order->billing_pincode }}
                                @endif
                            </div>
                            <div class="small mt-5 border-top pt-5">
                                Phone:
                                <span
                                    class="bold">{{ $order->shipping_contact_number ?: ($order->billing_contact_number ?? '-') }}</span>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        {{-- Items Table --}}
        <table class="bordered mt-10">
            <thead>
                <tr>
                    <th width="6%" class="text-center">#</th>
                    <th width="54%">Description</th>
                    <th width="10%" class="text-right">Qty</th>
                    <th width="15%" class="text-right">Unit Price</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                {{-- Order Items --}}
                <tr>
                    <td colspan="5" class="bold small bg-light" style="background-color:#f3f4f6;">Order Items</td>
                </tr>
                @php
                    $orderItemsSubtotal = 0;
                @endphp
                @foreach($order->items as $index => $item)
                    @php $orderItemsSubtotal += $item->subtotal; @endphp
                    <tr>
                        <td class="text-center small" style="color:#6b7280;">{{ $index + 1 }}</td>
                        <td>
                            <div class="bold" style="color:#111827;"> {{ $item->product->name }} -
                                {{ $item->product->category->name ?? '' }} </div>
                            <div class="small" style="color:#6b7280;">Unit: {{ $item->unit->name }}</div>
                        </td>
                        <td class="text-right bold">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-right bold" style="color:#6b7280;">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($item->ge_price, 2) }}
                        </td>
                        <td class="text-right bold" style="color:#111827;">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($item->ge_price * $item->quantity, 2) }}
                        </td>
                    </tr>
                @endforeach
                {{-- Order Items Subtotal & Tax --}}
                <tr style="background-color:#fafafa;">
                    <td colspan="4" class="text-right bold small">Products Subtotal</td>
                    <td class="text-right bold small">
                        {{ Helper::defaultCurrencySymbol() }}{{ number_format($orderItemsSubtotal, 2) }}</td>
                </tr>
                @php
                    // Calculate Tax for Order Items (Approximate based on global percentages applied to subtotal)
                    // Assuming Order Items are Tax Exclusive in subtotal
                    $itemsCgst = 0;
                    $itemsSgst = 0;
                    if ($order->cgst_percentage > 0)
                        $itemsCgst = $orderItemsSubtotal * ($order->cgst_percentage / 100);
                    if ($order->sgst_percentage > 0)
                        $itemsSgst = $orderItemsSubtotal * ($order->sgst_percentage / 100);
                @endphp
                @if($itemsCgst > 0)
                    <tr>
                        <td colspan="4" class="text-right bold small">CGST ({{ $order->cgst_percentage + 0 }}%)</td>
                        <td class="text-right bold small">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($itemsCgst, 2) }}</td>
                    </tr>
                @endif
                @if($itemsSgst > 0)
                    <tr>
                        <td colspan="4" class="text-right bold small">SGST ({{ $order->sgst_percentage + 0 }}%)</td>
                        <td class="text-right bold small">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($itemsSgst, 2) }}</td>
                    </tr>
                @endif
                <tr style="background-color:#fafafa;">
                    <td colspan="4" class="text-right bold small">Products Total</td>
                    <td class="text-right bold small">
                        {{ Helper::defaultCurrencySymbol() }}{{ number_format($orderItemsSubtotal + $itemsCgst + $itemsSgst, 2) }}</td>
                </tr>
                {{-- Services --}}
                @if($order->services->count() > 0)
                    <tr>
                        <td colspan="5" class="bold small bg-light"
                            style="background-color:#f3f4f6; border-top: 1px solid #e5e7eb;">Services</td>
                    </tr>
                    @php $servicesTotal = 0; @endphp
                    @foreach($order->services as $index => $service)
                        @php
                            $s = $service->service;
                            $priceIncludesTax = (int) ($service->price_includes_tax ?? ($s ? $s->price_includes_tax : 0));

                            $unitPrice = floatval($service->unit_price);
                            $qty = floatval($service->quantity);
                            $lineTotal = $unitPrice * $qty;

                            $cgstAmt = 0;
                            $sgstAmt = 0;
                            $cgstPct = 0;
                            $sgstPct = 0;

                            $taxSlab = $s ? $s->taxSlab : null;
                            if ($taxSlab) {
                                $cgstPct = $taxSlab->cgst;
                                $sgstPct = $taxSlab->sgst;
                            }
                            $totalTaxPct = $cgstPct + $sgstPct;

                            // Logic Change: Treat stored price as Tax Inclusive if tax exists
                            if ($totalTaxPct > 0) {
                                // Price is tax-inclusive: reverse-calculate base
                                $baseTotal = $lineTotal / (1 + $totalTaxPct / 100);
                                $exclUnitPrice = $qty > 0 ? ($baseTotal / $qty) : 0;
                            } else {
                                // Price is tax-exclusive: use as-is
                                $baseTotal = $lineTotal;
                                $exclUnitPrice = $unitPrice;
                            }

                            $taxAmt = $baseTotal * $totalTaxPct / 100;
                            if ($totalTaxPct > 0 && $taxAmt > 0) {
                                $cgstAmt = $taxAmt * ($cgstPct / $totalTaxPct);
                                $sgstAmt = $taxAmt * ($sgstPct / $totalTaxPct);
                            }

                            $servicesTotal += $baseTotal;
                        @endphp
                        <tr>
                            <td class="text-center small" style="color:#6b7280;">{{ $index + 1 }}</td>
                            <td>
                                <div class="bold" style="color:#111827;">{{ $s ? $s->name : 'Service' }}</div>
                            </td>
                            <td class="text-right bold">{{ number_format($service->quantity, 2) }}</td>
                            <td class="text-right bold" style="color:#6b7280;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($exclUnitPrice, 2) }}
                            </td>
                            <td class="text-right bold" style="color:#111827;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($baseTotal, 2) }}
                            </td>
                        </tr>
                        {{-- CGST and SGST rows --}}
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">CGST ({{ $cgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($cgstAmt, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">SGST ({{ $sgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($sgstAmt, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color:#fafafa;">
                        <td colspan="4" class="text-right bold small">Services Subtotal</td>
                        <td class="text-right bold small">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($servicesTotal + $cgstAmt + $sgstAmt, 2) }}</td>
                    </tr>
                @endif

                {{-- Packaging Materials --}}
                @if($order->packagingMaterials->count() > 0)
                    <tr>
                        <td colspan="5" class="bold small bg-light"
                            style="background-color:#f3f4f6; border-top: 1px solid #e5e7eb;">Packaging Materials</td>
                    </tr>
                    @php $packagingTotal = 0; @endphp
                    @foreach($order->packagingMaterials as $index => $pm)
                        @php
                            $p = $pm->packagingMaterial;
                            $priceIncludesTax = (int) ($pm->price_includes_tax ?? ($p ? $p->price_includes_tax : 0));

                            $unitPrice = floatval($pm->unit_price);
                            $qty = floatval($pm->quantity);
                            $lineTotal = $unitPrice * $qty;

                            $cgstAmt = 0;
                            $sgstAmt = 0;
                            $cgstPct = 0;
                            $sgstPct = 0;

                            $taxSlab = $p ? $p->taxSlab : null;
                            if ($taxSlab) {
                                $cgstPct = $taxSlab->cgst;
                                $sgstPct = $taxSlab->sgst;
                            }
                            $totalTaxPct = $cgstPct + $sgstPct;

                            // Logic Change: Treat stored price as Tax Inclusive if tax exists
                            if ($totalTaxPct > 0) {
                                $baseTotal = $lineTotal / (1 + $totalTaxPct / 100);
                                $exclUnitPrice = $qty > 0 ? ($baseTotal / $qty) : 0;
                            } else {
                                $baseTotal = $lineTotal;
                                $exclUnitPrice = $unitPrice;
                            }

                            $taxAmt = $baseTotal * $totalTaxPct / 100;
                            if ($totalTaxPct > 0 && $taxAmt > 0) {
                                $cgstAmt = $taxAmt * ($cgstPct / $totalTaxPct);
                                $sgstAmt = $taxAmt * ($sgstPct / $totalTaxPct);
                            }

                            $packagingTotal += $baseTotal;
                        @endphp
                        <tr>
                            <td class="text-center small" style="color:#6b7280;">{{ $index + 1 }}</td>
                            <td>
                                <div class="bold" style="color:#111827;">{{ $p ? $p->name : 'Material' }}</div>
                            </td>
                            <td class="text-right bold">{{ number_format($pm->quantity, 2) }}</td>
                            <td class="text-right bold" style="color:#6b7280;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($exclUnitPrice, 2) }}
                            </td>
                            <td class="text-right bold" style="color:#111827;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($baseTotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">CGST ({{ $cgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($cgstAmt, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">SGST ({{ $sgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($sgstAmt, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color:#fafafa;">
                        <td colspan="4" class="text-right bold small">Packaging Subtotal</td>
                        <td class="text-right bold small">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($packagingTotal + $cgstAmt + $sgstAmt, 2) }}</td>
                    </tr>
                @endif

                {{-- Other Items --}}
                @if($order->otherItems->count() > 0)
                    <tr>
                        <td colspan="5" class="bold small bg-light"
                            style="background-color:#f3f4f6; border-top: 1px solid #e5e7eb;">Other Items</td>
                    </tr>
                    @php $otherTotal = 0; @endphp
                    @foreach($order->otherItems as $index => $oi)
                        @php
                            $o = $oi->otherItem;
                            $priceIncludesTax = (int) ($oi->price_includes_tax ?? ($o ? $o->price_includes_tax : 0));

                            $unitPrice = floatval($oi->unit_price);
                            $qty = floatval($oi->quantity);
                            $lineTotal = $unitPrice * $qty;

                            $cgstAmt = 0;
                            $sgstAmt = 0;
                            $cgstPct = 0;
                            $sgstPct = 0;

                            $taxSlab = $o ? $o->taxSlab : null;
                            if ($taxSlab) {
                                $cgstPct = $taxSlab->cgst;
                                $sgstPct = $taxSlab->sgst;
                            }
                            $totalTaxPct = $cgstPct + $sgstPct;

                            // Logic Change: Treat stored price as Tax Inclusive if tax exists
                            if ($totalTaxPct > 0) {
                                $baseTotal = $lineTotal / (1 + $totalTaxPct / 100);
                                $exclUnitPrice = $qty > 0 ? ($baseTotal / $qty) : 0;
                            } else {
                                $baseTotal = $lineTotal;
                                $exclUnitPrice = $unitPrice;
                            }

                            $taxAmt = $baseTotal * $totalTaxPct / 100;
                            if ($totalTaxPct > 0 && $taxAmt > 0) {
                                $cgstAmt = $taxAmt * ($cgstPct / $totalTaxPct);
                                $sgstAmt = $taxAmt * ($sgstPct / $totalTaxPct);
                            }

                            $otherTotal += $baseTotal;
                        @endphp
                        <tr>
                            <td class="text-center small" style="color:#6b7280;">{{ $index + 1 }}</td>
                            <td>
                                <div class="bold" style="color:#111827;">{{ $o ? $o->name : 'Item' }}</div>
                            </td>
                            <td class="text-right bold">{{ number_format($oi->quantity, 2) }}</td>
                            <td class="text-right bold" style="color:#6b7280;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($exclUnitPrice, 2) }}
                            </td>
                            <td class="text-right bold" style="color:#111827;">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($baseTotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">CGST ({{ $cgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($cgstAmt, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right bold small text-muted">SGST ({{ $sgstPct + 0 }}%)</td>
                            <td class="text-right bold small text-muted">
                                {{ Helper::defaultCurrencySymbol() }}{{ number_format($sgstAmt, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background-color:#fafafa;">
                        <td colspan="4" class="text-right bold small">Other Items Subtotal</td>
                        <td class="text-right bold small">
                            {{ Helper::defaultCurrencySymbol() }}{{ number_format($otherTotal + $cgstAmt + $sgstAmt, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- Totals --}}
        <table style="margin-top:10px;">
            <tr>
                <td width="50%" valign="top">
                    <div class="box">
                        <div class="label mb-5">Deposit / Notes</div>
                        <div class="small border-bottom pb-5 mb-5">
                            Utencils Collected: {{ $order->utencils_collected ? 'Yes' : 'No' }}
                        </div>
                        <div class="small" style="min-height:20px;">{{ $order->remarks }}</div>
                    </div>
                </td>
                <td width="50%" valign="top">
                    <div class="box">
                        <table>
                            <tr>
                                <td class="small">Subtotal:</td>
                                <td class="text-right small bold">
                                    {{ Helper::defaultCurrencySymbol() }}{{ number_format($order->total_amount, 2) }}
                                </td>
                            </tr>
                            @foreach($order->charges as $ac)
                                <tr>
                                    <td class="small">{{ $ac->title }}:</td>
                                    <td class="text-right small bold">
                                        {{ Helper::defaultCurrencySymbol() }}{{ number_format($ac->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                            @if($order->discount_amount > 0)
                                <tr>
                                    <td class="small">Discount:</td>
                                    <td class="text-right small bold">
                                        -{{ Helper::defaultCurrencySymbol() }}{{ number_format($order->discount_amount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            @php $grandTotal = $order->net_amount; @endphp
                            <tr>
                                <td class="small bold border-top pt-5">Grand Total:</td>
                                <td class="text-right small bold border-top pt-5">
                                    {{ Helper::defaultCurrencySymbol() }}{{ number_format($grandTotal, 2) }}
                                </td>
                            </tr>
                            @if(!in_array($order->order_type, ['franchise', 'dealer']))
                                <tr>
                                    <td class="small bold" style="color:#15803d;">Amount Collected:</td>
                                    <td class="text-right small bold" style="color:#15803d;">
                                        {{ Helper::defaultCurrencySymbol() }}{{ number_format($order->amount_collected, 2) }}
                                    </td>
                                </tr>
                                @php $pending = $grandTotal - $order->amount_collected; @endphp
                                <tr>
                                    <td class="small bold">
                                        {{ $pending > 0 ? 'Pending:' : 'Balance:' }}
                                    </td>
                                    <td class="text-right small bold">
                                        {{ Helper::defaultCurrencySymbol() }}{{ number_format(abs($pending), 2) }}
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="mt-10 pt-10 border-top text-center">
            <div class="small bold" style="color:#b91c1c;">Thank You for Your Business!</div>
            <div class="small" style="color:#9ca3af;">This is a system-generated invoice.</div>
        </div>
    </div>
</body>

</html>
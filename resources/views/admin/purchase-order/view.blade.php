@extends('admin.layouts.app')
@push('links')
<style type="text/css">

</style>
@endpush




@section('main')

<table class="table align-middle datatable table-sm border-dark bg-white table-bordered nowrap" style="width:100%!important;vertical-align: middle;">

    <tbody>
        <tr style="vertical-align: middle;padding: 0;">
            <td rowspan="3" colspan="2" style="vertical-align: middle; text-align: center;padding: 0;width:100px; height:100px;">
                <img src="{{ asset('century-logo.png') }}" alt="Company Logo" width="200" height="170" style="vertical-align: middle; text-align: center;padding: 0;">
            </td>

            <th colspan="16" style="text-align:center;vertical-align: middle; font-size: 18px; font-weight: 600;height: 50px;">
                <p class="m-0">CENTURY PULP & PAPER </p>
                <p class="m-0">611 DLF Tower-B Plot No: 11 Jasola District Centre, New Delhi - 110025</p>
            </th>

             <td rowspan="3" colspan="2" style="vertical-align: middle; text-align: center;padding: 0;width:120px; height:100px;">
                <img src="{{ asset('century-logo.png') }}" alt="Company Logo" width="170" height="170" style="vertical-align: middle; text-align: center;padding: 0;">
            </td>
        </tr>
  
        
        <tr style="vertical-align:middle;">
            <th colspan="4" style="vertical-align:middle;height:40px;font-size: 14px; font-weight: 500; ">Purchase Order No.</th>
            <td colspan="4" style="vertical-align:middle;height:40px;font-size: 14px; font-weight: 300; ">{{$purchase_order->po_number}}</td>
            
            <td colspan="4" style="vertical-align:middle;height:40px;font-size: 14px; font-weight: 500; ">Purchase Order Date</td>
            <td colspan="4" style="vertical-align:middle;height:40px;font-size: 14px; font-weight: 300; ">{{$purchase_order->created_at}}</td>
        </tr>

        <tr>
            <td colspan="16" style="vertical-align:middle;height:50px;font-size: 14px; font-weight: 500; text-align: center;">ORDER BOOKING FORM - BOARD (PM-6)</td>
        </tr>

        <tr>
            <td colspan="5" style="width:25% !important;text-align: center; font-size: 14px; font-weight:500; height: 50px; vertical-align: middle; ">   <p class="m-0">DEALER / WHOLESALER NAME </p>
                <p class="m-0">(Will not be printed in invoice)</p>
            </td>

            <td colspan="5" style="width:25% !important;text-align: center; font-size: 14px; font-weight:500; height: 50px; vertical-align: middle; ">    <p class="m-0">BUYER/BILL TO PARTY </p> 
                <p class="m-0">(Has to be Dealer's Name)</p>
            </td>

            <td colspan="5" style="text-align: center; font-size: 14px; font-weight:500; height: 50px; vertical-align: middle; ">
                CONSIGNEE NAME & ADDRESS 
            </td>

            <td colspan="5" style="width:25% !important;text-align: center; font-size: 14px; font-weight:500; height: 50px; vertical-align: middle; ">
                DELIVERY ADDRESS / SHIP TO
            </td>
        </tr>


        <tr>
            <td rowspan="4" colspan="5" style="width:25%!important;text-align: center; font-size: 14px; font-weight:500; height: 150px; vertical-align: middle; ">   
                <p class="m-0">{{$purchase_order->fromParty->company_name}}</p>
                <p class="m-0">{{$purchase_order->fromParty->address}}</p>
                <p class="m-0">{{$purchase_order->fromParty->city}}-{{$purchase_order->fromParty->pincode}}</p>
            </td>

            <td colspan="5" style="width:25%!important;text-align: center; font-size: 14px; font-weight:500; height: 150px; vertical-align: middle; ">    
                <p class="m-0" style="">{{$purchase_order->billTo->company_name}} </p>
                <p class="m-0" style="">{{$purchase_order->billTo->address}} {{$purchase_order->billTo->city}}</p>
                <p class="m-0" style="">{{$purchase_order->billTo->state}} {{$purchase_order->billTo->pincode}}</p>
            </td>

            <td colspan="5" style="width:25%!important;text-align: center; font-size: 14px; font-weight:500; height: 150px; vertical-align: middle; ">
                <p class="m-0" style="">{{$purchase_order->consigneeParty->company_name}} </p>
                <p class="m-0" style="">{{$purchase_order->consigneeParty->address}} {{$purchase_order->consigneeParty->city}}</p>
                <p class="m-0" style="">{{$purchase_order->consigneeParty->state}} {{$purchase_order->consigneeParty->pincode}}</p>
            </td>

            <td rowspan="1" colspan="5" style="width:25 %!important;text-align: center; font-size: 14px; font-weight:500; height: 150px; vertical-align: middle; ">
                <p class="m-0" style="">{{$purchase_order->consigneeParty->company_name}} </p>
                <p class="m-0" style="">{{$purchase_order->consigneeParty->address}} {{$purchase_order->consigneeParty->city}}</p>
                <p class="m-0" style="">{{$purchase_order->consigneeParty->state}} {{$purchase_order->consigneeParty->pincode}}</p>
            </td>
        </tr>


        <tr>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">Delivery Place </td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->billTo->city}}</td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">Delivery Place    </td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->consigneeParty->city}}</td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">Delivery Place    </td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->shipTo->city}}</td>
            
        </tr>



        <tr>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">GST PID.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->billTo->gst}}</td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">GST PID.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->consigneeParty->gst}}</td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">GST PID.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;">{{$purchase_order->shipTo->gst}}</td>
        </tr>
        <tr>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">PAN No.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle;  height:35px;"></td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">PAN No.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle; "></td>
            <td style=" font-size: 14px; vertical-align: middle;  font-weight:500; height:35px;">PAN No.</td>
            <td colspan="4" style=" font-size: 14px; vertical-align: middle; "></td>
        </tr>
        {{-- <tr><td colspan="20" style="height:2px; "></td></tr> --}}


        <tr>

            <td style="width:60px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                Sr No.
            </td>

            <td style="width:120px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;" colspan="2">
                PRODUCT NAME
            </td>

            <td style="width:100px;height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                GSM
            </td>

            <td style="width:100px;height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                REEL/SHEET
            </td>

            <td style="width:120px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                GRAIN (L/S)
            </td>

            <td style="width:150px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;" colspan="3">
                SIZE (CMS)
            </td>

            <td style="width:100px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                Ream Weight
            </td>

            <td style="width:100px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                QTY (MT)
            </td>

            <td style="width:100px; height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;">
                DISCOUNT
            </td>

            <td style="height:35px; font-size:14px; text-align: center; vertical-align:middle;  font-weight: 500;" colspan="8">
                Remarks / Reel Dia (min/max) / Core / FSC / Packing Instructions
            </td>
        </tr>

        @php
            $count = 0; // Count of existing items
            $remaining = max(0, 10 - $count); // Calculate blank items
            $count = 1;
            $total = 0;
        @endphp
        @foreach($purchase_order->items as $item)
            <tr style="vertical-align: center;">

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">{{ $count }}</td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="2">{{$item->quality->name}}</td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    {{$item->gsm}}
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    {{$item->type}}
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">{{$item->grain}}</td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    {{$item->length_cm}}
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">X</td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    {{$item->width_cm}}
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">{{$item->ream_weight}}</td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    {{$item->quantity}}
                </td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">{{$item->discount}}</td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="8">{{$item->remarks}}</td>
            </tr>
            @php
                $total += $item->quantity;
                $count++;
            @endphp
        @endforeach



        

        @for($i = 0; $i < $remaining; $i++)
            <tr style="vertical-align: center;">

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">{{ $count }}</td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="2">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                   
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                   
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                   
                </td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="8"></td>
            </tr>
            @php
                $count++;
            @endphp
        @endfor


         <tr style="vertical-align: center;">

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="2">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                   
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                    
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;">
                   
                </td>

                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center; font-weight:700;">Total</td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;font-weight:700;">
                   {{$total}}
                </td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;"></td>
                <td style="font-size: 14px; text-align:center; height:30px; vertical-align:center;" colspan="8"></td>
            </tr>


        <tr>
            <td rowspan="3" colspan="3" style=" vertical-align: middle;">SPECIAL INSTRUCTIONS (ORDER / DELIVERY)</td>
            <td colspan="17" style=" vertical-align: middle;height:35px;"></td>
        </tr>
        
        <tr>
            <td colspan="17" style=" vertical-align: middle;height:35px;"></td>
        </tr>

        <tr>
            <td colspan="17" style=" vertical-align: middle;height:35px;"></td>
        </tr>

        <tr>
            <td colspan="5" style=" vertical-align: middle;height:35px;">SALES ORDER NUMBER</td>
            <td colspan="5" style=" vertical-align: middle;height:35px;"></td>
            <td colspan="5" style=" vertical-align: middle;height:35px;">S.O. DATE   </td>
            <td colspan="5" style=" vertical-align: middle;height:35px;"></td>
        </tr>
        <tr>
            <td colspan="20" style=" vertical-align: middle;height:35px;">Mills : Ghanshyamdham, P.O. Lalkuan- 262402, Distt. Nainital (Uttarakhand) Ph. : 05945-268044-46</td>
        </tr>
        <tr>
            <td colspan="20" style=" vertical-align: middle;height:35px;">Regd. Office : Century Bhawan, Dr. Annie Besant Road, Bombay - 400025                                                                      </td>
        </tr>
        <tr>
            <td colspan="20" style=" vertical-align: middle;height:35px;">DOC NO. MKT - F-02, ISSUE NO. 01                                                                       </td>
        </tr>

    </tbody>
</table>

@endsection


@push('scripts')

@endpush

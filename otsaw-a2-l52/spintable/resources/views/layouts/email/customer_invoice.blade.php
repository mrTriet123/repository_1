@extends('layouts.email.master')

@section('content')
<table  cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td>


        </td>
    </tr>

    <tr>
        <td class="content-block">
            <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #f8d5d5;">
                <tr>
                    <td style="font-size: 24px;padding-top: 20px;padding-bottom: 20px;">SGD ${{$total_amount}}</td>
                    <td align="right" style="padding-top: 20px;padding-bottom: 20px;">Thank you for choosing FIV, {{$issued_to}}!</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
            <td class="content-block">
                <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #828282;padding-bottom: 30px;">
                    <tr>
                        <td style="font-size: 18px;padding-top: 10px;padding-bottom: 20px;">FIV Receipt Summary</td>
                    </tr>
                    <tr>
                        <td>
                            Charge Per Hour:
                        </td>
                        <td align="right">
                            ${{$charge_per_hours}}
                        </td>
                    </tr>
            
                    <tr>
                        <td>
                            Total Number of Hours:
                        </td>
                        <td align="right">
                        @if($total_hours > 1)
                            {{$total_hours}} Hours
                        @else
                            {{$total_hours}} Hour
                        @endif
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Goods and Services Tax 7%:
                        </td>
                        <td align="right">
                            ${{$gst_amount}}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Discount Applied:
                        </td>
                        <td align="right">
                        @if($creadit_amount != 0)
                            ${{$creadit_amount}}
                        @else
                            N/A
                        @endif
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Promotion:
                        </td>
                        <td align="right">
                        @if($promotion != 0)
                            @if($discount_method == 1)
                                ${{$promotion}}
                            @else
                                {{$promotion}}%
                            @endif
                        @else
                            N/A
                        @endif
                        </td>
                    </tr>

                </table>
            </td>
    </tr>


        <tr>
                <td class="content-block">
                    <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #f8d5d5;padding-bottom: 30px;">
                        <tr>
                            <td style="font-size: 18px;padding-bottom: 20px;">Amount</td>
                            <td align="right"><strong>SGD ${{$total_amount}}</strong></td>
                        </tr>
                        <tr>
                            <td>
                                Payment Method
                            </td>
                            <td align="right">
                                {{$payment_method}} {{$card_no}}
                            </td>
                        </tr>

                    </table>
                </td>
        </tr>


<!-- Trip Detail -->
       <tr>
                <td class="content-block">
                    <table padding="0" margin="0" width="100%" style="padding-bottom: 10px;">
                        <tr>
                            <td style="font-size: 18px;padding-bottom: 20px;">Trip Detail</td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td>
                                Issued to:
                            </td>
                            <td align="right">
                                {{$issued_to}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Booking Code:
                            </td>
                            <td align="right">
                                {{$job_id}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Car Plate Number:
                            </td>
                            <td align="right">
                                {{$car_no}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Start Time:
                            </td>
                            <td align="right">
                                {{$start_time}}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Pick Up Location:
                            </td>
                            <td align="right">
                                {{$start_address}}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                End Time:
                            </td>
                            <td align="right">
                               {{$end_time}}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Drop Off Location:
                            </td>
                            <td align="right">
                                {{$end_address}}
                            </td>
                        </tr>


                    </table>
                </td>
        </tr>
<!-- End Trip Detail -->
</table>
@endsection
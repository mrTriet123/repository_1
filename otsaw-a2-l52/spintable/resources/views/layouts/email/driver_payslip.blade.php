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
                    <td style="padding-top: 20px;padding-bottom: 20px;"><strong>Date:</strong> {{$date}}</td>
                    <td align="right" style="padding-top: 20px;padding-bottom: 20px;">Thank you for being a part of FIV!</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
            <td class="content-block">
                <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #828282;padding-bottom: 20px;">
                    <tr>
                        <td style="font-size: 18px;padding-top: 10px;padding-bottom: 20px;" colspan="4">FIV Payslip</td>
                    </tr>
                    <tr>
                        <td width="35%">
                        </td>
                        <td width="15%">
                        <strong>Rate</strong>
                        </td>
                        <td width="15%">
                        <strong>Total</strong>
                        </td>
                        <td width="25%">
                        <strong>Total Amount</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 7px;">
                            <strong>Normal Hour(s):</strong>
                        </td>
                        <td style="padding-top: 7px;">$ {{$hourly_rate}}</td>
                        <td style="padding-top: 7px;">{{$total_hours}}</td>
                        <td style="padding-top: 7px;">
                          $ {{money_format('%.2n',$total_hours * $hourly_rate)}}
                        </td>
                    </tr>
            
                    <tr>
                        <td style="padding-top: 7px;">
                            <strong>Midnight:</strong>
                        </td>
                        <td style="padding-top: 7px;">$ {{$midnight_rate}}</td>
                        <td style="padding-top: 7px;">{{$midnight}}</td>
                        <td style="padding-top: 7px;">
                        $ {{money_format('%.2n',$midnight * $midnight_rate)}}
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top: 7px;">
                            <strong>Total Penalty:</strong>
                        </td>
                        <td></td>
                        <td></td>
                        <td style="padding-top: 7px;">
                            $ {{$total_penalties}}
                        </td>
                    </tr>
                </table>
            </td>
    </tr>


        <tr>
                <td class="content-block">
                    <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #f8d5d5;padding-bottom: 5px;">
                        <tr>
                            <td style="padding-bottom: 10px;" width="72%"><strong>Total Earned</strong></td>
                            <td></td>
                            {{-- <td><strong>SGD $ {{money_format('%.2n',$payslip['total_earn'])}}</strong></td> --}}
                            <td><strong>$ {{money_format('%.2n',($total_hours * $hourly_rate) + ($midnight * $midnight_rate) - $total_penalties)}}</strong></td>
                            {{-- ($payslip['total_hours'] * $payslip['hourly_rate']) + ($payslip['midnight'] * $payslip['midnight_rate']) --}}
                        </tr>

                    </table>
                </td>
        </tr>

        <tr>
            <td class="content-block">
            <table padding="0" margin="0" width="100%" style="padding-bottom: 5px;">
                <tr>
                    <td style="padding-top: 0px;" width="72%">
                        <table padding="0" margin="0" width="100%">
                        <tr><td>
                            <strong>Security Deposit ($500):</strong>
                        </td></tr></table>
                    </td>
                    <td style="padding-top: 0px;">
                        <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #f8d5d5;padding-bottom: 10px;">
                        <tr><td>
                        {{$deposit}}
                        </td></tr></table>
                    </td>
                </tr>
            </table>
            </td>
        </tr>

        <tr>
            <td class="content-block">
            <table padding="0" margin="0" width="100%" style="padding-bottom: 5px;">
                <tr>
                    <td style="padding-top: 0px;" width="72%">
                    <table padding="0" margin="0" width="100%">
                        <tr><td>
                            <strong>Total Payout:</strong>
                        </td></tr></table>
                    </td>
                    <td style="padding-top: 0px;">
                        <table padding="0" margin="0" width="100%" style="border-bottom: 1px solid #f8d5d5;padding-bottom: 10px;">
                        <tr><td>
                        <strong>SGD $ {{money_format('%.2n',$total_earn)}}</strong>
                        </td></tr></table>
                        
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
                            <td style="font-size: 18px;">PaySlip Details</td>
                            <td align="right"></td>
                        </tr>
                        <tr><td style="border-bottom: 1px solid #828282;" colspan="2"></td></tr>
                        <tr>
                            <td style="padding-top: 20px;">
                                Issued By: 
                            </td>
                            <td align="right" style="padding-top: 20px;">
                                (FIV) OTSAW DIGITAL PTE LTD
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Issued to
                            </td>
                            <td align="right">
                                {{$name}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Issued Date:
                            </td>
                            <td align="right">
                                {{$todayDateTime}}
                            </td>
                        </tr>
                     {{--    <tr>
                            <td>
                                Start Time:
                            </td>
                            <td align="right">
                                {{$start_time}}
                            </td>
                        </tr> --}}

                    </table>
                </td>
        </tr>
<!-- End Trip Detail -->
</table>
@endsection
<?php
    $ci =& get_instance();
    $ci->load->helper('az_config');
    $ci->load->helper('az_core');
    $store_name = az_get_config('store_name');
    $store_description = az_get_config('store_description');
?>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>
            AZ Kasir - <?php echo azlang('Print Nota');?>
        </title>
        <style type="text/css">
            html {
                font-family: "Verdana";
            }
            .content {
                width: 80mm;
                font-size: 12px;
                padding: 5px;
            }
            .content .title {
                text-align: center;
            }
            .content .head-desc {
                margin-top: 10px;
                display: table;
                width: 100%;
            }
            .content .head-desc > div {
                display: table-cell;
            }
            .content .head-desc .user {
                text-align: right;
            }
            .content .nota {
                text-align: center;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            .content .separate {
                margin-top: 10px;
                margin-bottom: 15px;
                border-top: 1px dashed #000;
            }
            .content .transaction-table {
                width: 100%;
                font-size: 12px;
            }
            .content .transaction-table .name {
                width: 185px;
            }
            .content .transaction-table .qty {
                text-align: center;
            }
            .content .transaction-table .sell-price, .content .transaction-table .final-price {
                text-align: right;
                width: 65px;
            }
            .content .transaction-table tr td {
                vertical-align: top;
            }
            .content .transaction-table .price-tr td {
                padding-top: 7px;
                padding-bottom: 7px;
            }
            .content .transaction-table .discount-tr td {
                padding-top: 7px;
                padding-bottom: 7px;
            }
            .content .transaction-table .separate-line {
                height: 1px;
                border-top: 1px dashed #000;
            }
            .content .thanks {
                margin-top: 15px;
                text-align: center;
            }
            .content .azost {
                margin-top:5px;
                text-align: center;
                font-size:10px;
            }
            @media print {
                @page  { 
                    width: 80mm;
                    margin: 0mm;
                }
            }

        </style>
    </head>
    <body onload="window.print();">
        <div class="content">
            <div class="title">
                <?php 
                    echo htmlspecialchars($store_name);
                    echo "<br>";
                    echo htmlspecialchars($store_description);
                ?>
            </div>

            <div class="head-desc">
                <div class="date">
                    <?php
                        echo Date("d-m-Y H:i", strtotime($data->transaction_date));
                    ?>
                </div>
                <div class="user">
                    <?php
                        echo $data->name;
                    ?>
                </div>
            </div>
            
            <div class="nota">
                <?php echo $data->code;?>
            </div>

            <div class="separate"></div>

            <div class="transaction">
                <table class="transaction-table" cellspacing="0" cellpadding="0">
                    <?php
                        $arr_discount = array();
                        foreach ($transaction->result() as $key => $value) {
                            echo "<tr>";
                            echo "  <td class='name'>".$value->name."</td>";
                            echo "  <td class='qty'>".$value->qty."</td>";
                            echo "  <td class='sell-price'>".az_thousand_separator($value->sell_price)."</td>";
                            echo "  <td class='final-price'>".az_thousand_separator($value->qty * $value->sell_price)."</td>";
                            echo "</tr>";    

                            if ($value->discount > 0) {
                                $arr_discount[] = $value->discount;     
                            } 
                        }
                    
                        foreach ($arr_discount as $key => $value) {
                            echo '
                                <tr>
                                    <td colspan="2">
                                    </td>
                                    <td class="final-price">
                                        DISKON '.($key + 1).'
                                    </td>
                                    <td class="final-price">
                                        '.az_thousand_separator($value).'
                                    </td>
                                </tr>';                            
                        }
                    ?>
                    <tr class="price-tr">
                        <td colspan="4">
                            <div class="separate-line"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="final-price">
                            <?php echo azlang('Sell Price');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data->total_sell_price);?>
                        </td>
                    </tr>
                    <?php
                        if ($data->total_discount > 0) {
                    ?>
                    <tr>
                        <td colspan="3" class="final-price">
                            <?php echo azlang('DISCOUNT');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data->total_discount);?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <tr class="discount-tr">
                        <td colspan="4">
                            <div class="separate-line"></div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="final-price">
                            TOTAL
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data->total_final_price);?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="final-price">
                            <?php echo azlang('Pay');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data->total_cash);?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="final-price">
                            <?php echo azlang('Change');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data->total_change);?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="thanks">
                ~~~ <?php echo azlang('Thank You');?> ~~~
            </div>
            <div class="azost">
                www.azostech.com
            </div>
        </div>
    </body>
</html>
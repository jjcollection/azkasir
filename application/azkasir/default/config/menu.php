<?php

	$config['menu'] = array(
                array(
                    "name" => azlang('DASHBOARD'),
                    "icon" => "dashboard.png",
                    "url" => "",
                    "submenu" => array()
                ),
                array(
                    "name" => azlang("SUPPLIER"),
                    "icon" => "supplier.png",
                    "url" => "supplier",
                    "submenu" => array()
                ),
                array(
                    "name" => azlang("CUSTOMER"),
                    "icon" => "customer.png",
                    "url" => "customer",
                    "submenu" => array()
                ),
                array(
                    "name" => azlang("PRODUCT"),
                    "icon" => "product.png",
                    "url" => "product",
                    "submenu" => array(
                    	array(
                    		"name" => azlang('PRODUCT CATEGORY'),
                    		"icon" => "",
                    		"url" => "product_category",
                    		"submenu" => array()
                    	),
                    	array(
                    		"name" => azlang('PRODUCT UNIT'),
                    		"icon" => "",
                    		"url" => "product_unit",
                    		"submenu" => array()
                    	),
                    	array(
                    		"name" => azlang('DATA PRODUCT'),
                    		"icon" => "",
                    		"url" => "product",
                    		"submenu" => array()
                    	)
                    )
                ),
                array(
                    "name" => azlang('STOCK'),
                    "icon" => "inout.png",
                    "url" => "stock",
                    "submenu" => array(
                        array(
                            "name" => azlang('STOCK IN'),
                            "icon" => "",
                            "url" => "stock",
                            "submenu" => array()
                        ),
                        array(
                            "name" => azlang('STOCK OUT'),
                            "icon" => "",
                            "url" => "stock/out",
                            "submenu" => array()
                        )
                    )
                ),
                array(
                    "name" => azlang('TRANSACTION'),
                    "icon" => "rp.png",
                    "url" => "transaction",
                    "submenu" => array(
                    	array(
                    		"name" => azlang('TRANSACTION SALE'),
		                    "icon" => "",
		                    "url" => "transaction",
		                    "submenu" => array()
                    	)
                    )
                ),
                array(
                    "name" => azlang('REPORT'),
                    "icon" => "report.png",
                    "url" => "transaction_list",
                    "submenu" => array(
                    	array(
                    		"name" => azlang('SALES REPORT'),
		                    "icon" => "",
		                    "url" => "transaction_list",
		                    "submenu" => array()
                    	),
                        array(
                            "name" => azlang('REPORT STOCK IN'),
                            "icon" => "",
                            "url" => "report_stock",
                            "submenu" => array()
                        ),
                        array(
                            "name" => azlang('REPORT STOCK OUT'),
                            "icon" => "",
                            "url" => "report_stock/out",
                            "submenu" => array()
                        ),
                        // array(
                        //     "name" => "LAPORAN STOK PRODUK",
                        //     "icon" => "",
                        //     "url" => "report_stock_product",
                        //     "submenu" => array()
                        // )
                    )
                ),
                array(
                    "name" => azlang('SETTING'),
                    "icon" => "setting.png",
                    "url" => "setting",
                    "submenu" => array()
                ),
                array(
                    "name" => azlang('USER'),
                    "icon" => "user.png",
                    "url" => "user",
                    "submenu" => array()
                )
            );


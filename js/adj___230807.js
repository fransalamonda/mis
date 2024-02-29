/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


(function() {
            $( "#delvdate" ).datepicker({ dateFormat: 'dd/mm/yy' });
            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
            var delv_date = $('#delv_date').val();
            var loading = $(".loading");
            var base_url    = location.origin+"/mis/";
            /*
             * popup docket
             */
            $("form#query").on('click', '.q_so', function(e){
                e.preventDefault(e);
                //var loading = $(".loading");
                loading.show();
                jQuery.ajax({
                    url: base_url+"ajax/batch_request_adjustment_docket.php",
                    success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                $('#test').modal('show');
                                $("#popup_table").html(obj.msg);
                                $("#mix_wrapper").html(obj.mix_str);
                            }
                            else if(obj.status === 0){
                                show_alert_ms($("#msg"),0,obj.msg);
                //                goToMessage();
                            }
                            loading.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading_object.hide(); 
                            alert(thrownError);
                        }
                });
            });
            /*
             * add docket
             */
            $("#test").on('click', '#add_docket', function(e){
                var docket_no = $('input[name="docket"]:checked').val()
                $("#docket_no").val(docket_no);
                
            });

            var bar = $('.bar');
            var percent = $('.percent');
            var status = $('#status');
 //           var delv_date = $('#delv_date').val();
            var loading = $(".loading");
            /*
             * klik query delivery schedule adjustment
             */
            $('form#query').ajaxForm({
                beforeSend: function() {
                    loading.show();
                },
                complete: function(xhr) {
                    $('div.loading').hide();
                    console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status == '1'){
                        $('div#messages').html("<p>"+obj.msg+"</p>");
                        $('div#messages').removeClass("n_error").addClass("n_ok");
                        $('input#plant_id').val(obj.plant_id);
                        $('.plant_id').html(obj.plant_id);
                        $('input#so_no').val(obj.so_no);
                        $('input#docket_no').val(obj.docket_no);
//                        $('#sche_date').html(obj.sche_date);
//                        $('#s_date').val(obj.sche_date);
                        $('#docket').html(obj.docket);
                        $('#today_b_request').html(obj.today_b_request);
                        $('#out_volume').html(obj.out_volume);
                        $('#cust_id').html(obj.cust_id+", "+obj.cust_name);
                        $('#cust_name').html(obj.cust_name);
                        $('#proj_no').html(obj.proj_no+", "+obj.proj_address);
                        $('#proj_name').html();
                        $('#proj_loc').html(obj.proj_loc);
                        $('#prod_code').html(obj.product_code);
                        
//                        $('#varian').html(obj.varian)
                        $('div#message_batch').hide();
                        $('div#batch-add-adjustmen').hide();
                        $('div#messages').fadeIn();
                        $('div#batch-add-adjustmen').slideDown();
                        $('select#fa').val('0');
                        $('select#cement').val('0');
                        $('select#agg1').val('0');
                        $('select#agg2').val('0');
                        $('select#agg3').val('0');
                        $('select#msand').val('0');
                        $('select#sand').val('0');
                        $('select#water').val('0');
                        $('select#rt6p').val('0');
                        $('select#vis1003').val('0');
						$('select#gencb10').val('0');
						$('select#flbrpf34').val('0');
                        $('select#vis3660lr').val('0');
                        $('select#p83').val('0');
                        $('select#SIKAMENT183').val('0');
                        $('select#genr8212').val('0');
                        $('select#get702"').val('0');
                        $('select#genb1714"').val('0');
                        $('select#flbnf15"').val('0');
                        $('select#flbpd19"').val('0');

                        
                    }
                    else if(obj.status == '0'){
                        $('div#messages').hide();$('div#batch-add-adjustmen').slideUp();
                        $('div#messages').removeClass("n_ok").addClass("n_error");
                        $('div#messages').html("<p>"+obj.msg+"!</p>");
                        $('div#messages').fadeIn();$('div#detail').hide();
                    }
                }
            });
            $('form#detail').ajaxForm({
                beforeSend: function() {
                        loading.show();
                    },
                complete: function(xhr) {
                 console.log(xhr.responseText);
                    $('div.loading').hide();
                    loading.show();
                    var obj = jQuery.parseJSON(xhr.responseText);
                    console.log(obj.row);
                    if(obj.status == '1'){
                        $('div#message_batch').html("<p>"+obj.msg+"</p>");
                        $('div#message_batch').removeClass("n_error").addClass("n_ok");
                        $('div#message_batch').fadeIn();
                        $('input#docket_no').val('');
                        $('#today_b_request').html(obj.today_b_request);
//                        $('#out_volume').html(obj.out_volume);
                        $('div#detail').hide();
                        $('div#messages').slideDown();
                    }
                    else if(obj.status == '0'){
                        $('div#message_batch').hide();
                        $('div#message_batch').removeClass("n_ok").addClass("n_error");
                        $('div#message_batch').html("<p>"+obj.msg+"!</p>");
                        $('div#message_batch').fadeIn();goToMessage();
                    }
                    loading.hide();
                }
            });
            
            $('form#query_not_d').ajaxForm({
                beforeSend: function() {
                    loading.show();
                },
                success: function() {
                    var percentVal = '100%';
                    bar.width(percentVal)
                    percent.html(percentVal);
                },
                complete: function(xhr) {
                    $('div.loading').hide();
                    console.log(xhr.responseText);
                    var obj = jQuery.parseJSON(xhr.responseText);
                    if(obj.status == '1'){
                        $('div#msg_docket').html("<p>"+obj.msg+"</p>");
                        $('div#msg_docket').removeClass("n_error").addClass("n_ok");
                        $('input#plant_id').val(obj.plant_id);
                        $('.plant_id').html(obj.plant_id);
                        $('input#so').val(obj.so_no);
                        $('input#docket').val(obj.docket);
                        $('#dn').        html(obj.docketn);
                        $('#cust').           html(obj.cust_id+", "+obj.cust_name);
                        $('#cust_name').html(obj.cust_name);
                        $('#proj').           html(obj.proj_no+", "+obj.proj_address);
                        $('#proj_name').html();
                        $('#loc').            html(obj.proj_loc);
                        $('#pro').            html(obj.product_code);
                        
                        $('div#msg_docket').hide();
                        $('div#batch-add-no-docket').hide();
                        $('div#msg_docket').fadeIn();
                        $('div#batch-add-no-docket').slideDown();
                        $('select#fa').val('0');
                        $('select#cement').val('0');
                        $('select#agg1').val('0');
                        $('select#agg2').val('0');
                        $('select#agg3').val('0');
                        $('select#msand').val('0');
                        $('select#sand').val('0');
                        $('select#water').val('0');
                        $('select#rt6p').val('0');
                        $('select#vis1003').val('0');
						$('select#gencb10').val('0');
						$('select#flbrpf34').val('0');
                        $('select#vis3660lr').val('0');
                        $('select#p83').val('0');
                        $('select#SIKAMENT183').val('0');
                        $('select#genr8212').val('0');
                        $('select#get702"').val('0');
                        $('select#genb1714"').val('0');
                        $('select#flbnf15"').val('0');
                        $('select#flbpd19"').val('0');
                    }
                    else if(obj.status == '0'){
                        $('div#msg_docket').hide();$('div#batch-add').slideUp();
                        $('div#msg_docket').removeClass("n_ok").addClass("n_error");
                        $('div#msg_docket').html("<p>"+obj.msg+"!</p>");
                        $('div#msg_docket').fadeIn();$('div#query_not_d').hide();
                    }
                }
            });
            $('form#detail_not').ajaxForm({
                beforeSend: function() {
                        loading.show();
                    },
                complete: function(xhr) {
                 console.log(xhr.responseText);
                    $('div.loading').hide();
                    loading.show();
                    var obj = jQuery.parseJSON(xhr.responseText);
                    console.log(obj.row);
                    if(obj.status == '1'){
                        $('div#message_batch_not').html("<p>"+obj.msg+"</p>");
                        $('div#message_batch_not').removeClass("n_error").addClass("n_ok");
                        $('div#message_batch_not').fadeIn();
                        $('input#docket_no').val('');
                        $('#today_b_request').html(obj.today_b_request);
                        $('div#detail_not').hide();
                    }
                    else if(obj.status == '0'){
                        $('div#message_batch_not').hide();
                        $('div#message_batch_not').removeClass("n_ok").addClass("n_error");
                        $('div#message_batch_not').html("<p>"+obj.msg+"!</p>");
                        $('div#message_batch_not').fadeIn();goToMessage();
                    }
                    loading.hide();
                }
            });
            
            
            $("form#query_not").on('click', '.q_cust', function(e){
                e.preventDefault(e);
                //var loading = $(".loading");
                loading.show();
                
                jQuery.ajax({
                    url: base_url+"ajax/batch_request_adjustment_cust.php",
                    success:function(response){
                            var obj = jQuery.parseJSON(response);
                            if(obj.status === 1){
                                $('#test').modal('show');
                                $("#popup_table").html(obj.msg);
                                $("#mix_wrapper").html(obj.mix_str);
                            }
                            else if(obj.status === 0){
                                show_alert_ms($("#msg"),0,obj.msg);
                //                goToMessage();
                            }
                            loading.hide();
                        },
                        error:function (xhr, ajaxOptions, thrownError){
                            loading_object.hide(); 
                            alert(thrownError);
                        }
                });
            });
            $("#cust").on('click', '#add_cust', function(e){
                var customer_id = $('input[name="cust"]:checked').val()
                $("#cust_id").val(customer_id);  
            });
            
            
            
            $('form#query_self_usage').ajaxForm({
                beforeSend: function() {
                        loading.show();
                    },
                complete: function(xhr) {
                 console.log(xhr.responseText);
                    $('div.loading').hide();
                    loading.show();
                    var obj = jQuery.parseJSON(xhr.responseText);
                    console.log(obj.row);
                    if(obj.status == '1'){
                        $('div#message_su').html("<p>"+obj.msg+"</p>");
                        $('div#message_su').removeClass("n_error").addClass("n_ok");
                        $('div#message_su').fadeIn();
                        $('input#docket_no').val('');
                        $('#today_b_request').html(obj.today_b_request);
//                        $('#out_volume').html(obj.out_volume);
                        $('div#detail').hide();
                        $('select#fa').val('0');
                        $('select#cement').val('0');
      //                   $('select#agg1').val('0');
      //                   $('select#agg2').val('0');
      //                   $('select#agg3').val('0');
                        // $('select#agg1vas').val('0');
      //                   $('select#msand').val('0');
      //                   $('select#sand').val('0');
      //                   $('select#water').val('0');
      //                   $('select#rt6p').val('0');
      //                   $('select#vis1003').val('0');
						// $('select#flbrpf34').val('0');
      //                   $('select#vis3660lr').val('0');
                        // $('select#gencb10').val('0');
                        // $('select#gencb11').val('0');
      //                   $('select#p83').val('0');
      //                   $('select#SIKAMENT183').val('0');
      //                   $('select#get702"').val('0');
                        // $('select#mast1007').val('0');
                        // $('select#genb1714"').val('0');
                        // $('select#flbnf15"').val('0');
                        // $('select#flbpd19"').val('0');
                    }
                    else if(obj.status == '0'){
                        $('div#message_su').hide();
                        $('div#message_su').removeClass("n_ok").addClass("n_error");
                        $('div#message_su').html("<p>"+obj.msg+"!</p>");
                        $('div#message_su').fadeIn();goToMessage();
                    }
                    loading.hide();
                }
            });

            function goToMessage(){
                $("body, html").animate({ 
                    scrollTop: $("#detail").offset().top 
                }, 600);
            }
        })();
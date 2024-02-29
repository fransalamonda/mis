/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
* print the message (Error,info,warning,success)
* @param {object} item
* @param {string} msg 
* @param {int} type 
* @returns {undefined}
*/
function print_msg(item,type,msg){
    if(type === 0){
        item.removeClass("alert-success").removeClass("alert-info").removeClass("alert-warning");
        item.addClass("alert-danger");
    }
    if(type === 1){
        item.removeClass("alert-danger").removeClass("alert-info").removeClass("alert-warning");
        item.addClass("alert-success");
    }
    if(type === 2){
        item.removeClass("alert-danger").removeClass("alert-success").removeClass("alert-warning");
        item.addClass("alert-info");
    }
    if(type === 3){
        item.removeClass("alert-danger").removeClass("alert-success").removeClass("alert-info");
        item.addClass("alert-warning");
    }
    if(item.is(":visible")){
        item.html(msg);
        if(type === 0){
            item.effect( "shake" );
        }
        else{
            item.fadeIn();
        }
    }
    else{
        item.html(msg);
        item.show();
    }
}
/*
 * 
 * @param {type} base_url
 * @param {type} url
 * @param {type} data
 * @param {type} mod    :   reset
 * @param {type} itemmod : might be form object, etc
 * @returns {undefined}
 */
function process_ajax(base_url,url,data,mod,loading_object,msg_object,itemmod){
    var content="";
    loading_object.show();
    jQuery.ajax({
        type: "POST", // HTTP method POST or GET
        url: base_url+url, //Where to make Ajax calls
        dataType:"text", // Data type, HTML, json etc.
        data:data, //Form variables
        success:function(response){
            var obj = jQuery.parseJSON(response);
            if(obj.status === 1){
                content   =   "<h4 class=\"block\"><b>Success !</b></h4><p>"+obj.msg+"</p>";
                print_msg(msg_object,1,content);
                if(mod === "reset"){
                    itemmod[0].reset();
                }
                if(mod === "refresh"){
                    window.setTimeout(function(){
                        window.location.href = document.URL;
                    }, 1500);
                }
            }
            else if(obj.status === 0){
                content   =   "<h4 class=\"block\"><b>Error !</b></h4><p>"+obj.msg+"</p>";
                print_msg(msg_object,0,content);
            }
            loading_object.hide();
        },
        error:function (xhr, ajaxOptions, thrownError){
            loading_object.hide(); 
            alert(thrownError);
        }
    });
}


$(document).ready(function(){
    var base_url    = location.origin;
    base_url+="/mis";
    var loading = $(".loading");



    /*
     * edit Driver
     */
    $(".tbl_driver").on("click", ".edit_driver", function(event){
        //hide form add code
        $(".add_code_wrapper").hide();
        //show form edit code
        $(".edit_code_wrapper").hide();
        $(".edit_code_wrapper").fadeIn();
        event.preventDefault();
        $("#driverid_edit").val($(this).data('driverid'));
        $("#nopol_edit").val($(this).data('nopol'));
        $("#namaDriver_edit").val($(this).data('drivername'));
        $("#noTelp_edit").val($(this).data('notelp'));

        var nopol = $(this).data('nopol');
        var value=0;
        $("#nopol_edit option").each(function()
        {
            value = parseInt($(this).val());
            if(value === cate){
                $(this).attr("selected","selected");
            }
            else{
                $(this).removeAttr("selected");
            }
        });
        
      });



    /*
     * form add new driver
     */
    $("#frm_add_new_driver").submit(function(e){
        e.preventDefault();
        //properties

        var flatno = $("#flatNo");
        var driverName = $("#namaDriver");
        var nomorTelp  = $("#noTelp");

        if(flatno.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Plat Nomor Kendaraan</i> harus diisi";
            print_msg($("#messages"),0,msg);
            flatno.focus();return false;
        }
        if(driverName.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Nama Driver</i> harus diisi";
            print_msg($("#messages"),0,msg);
            driverName.focus();return false;
        }
        if(nomorTelp.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Nomor Telepon</i> harus diisi";
            print_msg($("#messages"),0,msg);
            nomorTelp.focus();return false;
        }
        
        var file = "/ajax/acceptance_new_driver.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_new_driver");
        var data = "flatno="+flatno.val()+"&namaDriver="+driverName.val()+"&noTelp="+nomorTelp.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
    });

    /*
     * delete driver
     */
    $(".tbl_driver").on("click", ".delete_driver", function(event){
        event.preventDefault();
        var id = $(this).data('id');
        var file = "/ajax/acceptance_delete_driver.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_new_driver");
        var data = "id_driver="+id;
         BootstrapDialog.confirm('Hapus data dengan driver id : <b>'+id+"</b> ?", function(result){
             if(result) {
                ajax_url        = "member/delete";
                ajax_data       = "idmember="+id;
                var mod         = "redirect";
                var itemmod     = "member";
                process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
            }
            else{return false;}
         });
      });



    /*
     * form edit driver
     */
    $("#frm_edit_driver").submit(function(e){
        e.preventDefault();
        //properties
        var driverid = $("#driverid_edit");
        var nopol    = $("#nopol_edit");
        var namadriver  = $("#namaDriver_edit");
        var notelp = $("#noTelp_edit");

        if(nopol.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Plat Nomor</i> harus diisi";
            print_msg($("#messages"),0,msg);
            nopol.focus();return false;
        }
        if(namadriver.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Nama Driver</i> harus diisi";
            print_msg($("#messages"),0,msg);
            namadriver.focus();return false;
        }
        if(notelp.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Nomor telepon</i> harus diisi";
            print_msg($("#messages"),0,msg);
            notelp.focus();return false;
        }
        
        var file = "/ajax/acceptance_edit_driver.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_new_driver");
        var data = "id="+driverid.val()+"&pol="+nopol.val()+"&driver="+namadriver.val()+"&telp="+notelp.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);

        
    });






    });


    
    
   
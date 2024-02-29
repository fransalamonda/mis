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
     * review return truck 
     */
    $("#jns_proses").change(function(){
        var wrapper = $(".kode_proses");
        var label = "";
        var remark = $(".remark");
        if($(this).val() === ''){
            wrapper.empty();
            remark.hide();
        }
        else{
            var url = "/ajax/acceptance_retrieve_condition.php";
            var data = "idcode="+$( "#jns_proses" ).val();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: base_url+url, //Where to make Ajax calls
                dataType:"text", // Data type, HTML, json etc.
                data:data, //Form variables
                success:function(response){
                    wrapper.empty();
//                    wrapper.append(label);
                    
                    var obj = jQuery.parseJSON(response);
                    if(obj.status === 1){
                        label = "<label for=\"category_proses\">Category:</label>";
                        wrapper.append(label);
                        wrapper.append(obj.msg);
                    }
                    else if(obj.status === 0){
                        
                    }
                    loading.hide();
                },
                error:function (xhr, ajaxOptions, thrownError){
                    loading.hide(); 
                    alert(thrownError);
                }
            });
            remark.show();
        }
        
        
        
    });
    $("#truck_return").submit(function(){
        var jns_proses = $("#jns_proses");
        var proses  =   $("#proses");
        var kode_proses = $("#kode_proses");
        var remark = $("#remark");
        var dev = $("#dev");
//        alert (proses.val());
        if(proses.val() > dev.val()){
            BootstrapDialog.alert("Acceptance Volume <= Delv Volume");
            jns_proses.focus();
            return false;
        }
        if(jns_proses.val() === ""){
//            alert("Anda harus pilih jenis proses");
            BootstrapDialog.alert("You must select the type of process!");
            jns_proses.focus();
            return false;
        }
        if(proses.val() === ""){
            BootstrapDialog.alert("You must input Acceptance Volume!");
            jns_proses.focus();
            return false;
        }
        else{
            if(jns_proses.val() === '2' || jns_proses.val() === '2' || jns_proses.val() === '3'){
                if(kode_proses.val() === ""){
//                    alert("Anda harus pilih kode proses");
                    BootstrapDialog.alert("Anda harus pilih kategori");
                    kode_proses.focus();
                    return false;
                }
                if(remark.val() === ""){
                    BootstrapDialog.alert("Remarks Harus diisi");
                    remark.focus();
                    return false;
                }
            }
        }
    });
    
//    download batch transaction
    var loading = $(".loading");
    var msg = $("#messages");
    $("#retrieve_bt").submit(function (e) {
        e.preventDefault();
        var delvdate = $("#delvdate");
        if (delvdate.val() === "") {
            
            msg.hide();
            msg.empty();
            msg.removeClass("alert-success").removeClass("alert-info").removeClass("alert-warning");
            msg.append("<b>ERROR!</b><br/>Kolom <i>Delivery Date</i> harus diisi");
            msg.fadeIn();
            
            delvdate.focus();
            return false;
        }
        $(".input").slideUp();
        loading.show();
        $(".download").fadeIn();
        loading.fadeOut();
    });
    
    $(".docket_n").click(function(e){
        e.preventDefault();
        var delvdate = $("#delvdate");
        window.open("http://localhost/mis/act/export_to_csv.php?code=1&delvdate="+delvdate.val());
        $(".docket_n").hide();
        $(".mat_is_n").show();
        check_all_button();
    });
    $(".mat_is_n").click(function(e){
        e.preventDefault();
        var delvdate = $("#delvdate");
        window.open("http://localhost/mis/act/export_to_csv.php?code=2&delvdate="+delvdate.val());
        $(".mat_is_n").hide();
        check_all_button();
    });
    $(".docket_m").click(function(e){
        e.preventDefault();
        var delvdate = $("#delvdate");
        window.open("http://localhost/mis/act/export_to_csv.php?code=3&delvdate="+delvdate.val());
        $(".docket_m").hide();
        $(".mat_is_m").show();
        check_all_button();
    });
    $(".mat_is_m").click(function(e){
        e.preventDefault();
        var delvdate = $("#delvdate");
        window.open("http://localhost/mis/act/export_to_csv.php?code=4&delvdate="+delvdate.val());
        $(".mat_is_m").hide();
        check_all_button();
    });
    
    //function check all button already push
    function check_all_button(){
        if($('.docket_n').is(":visible") === false && $('.mat_is_n').is(":visible") === false && $('.docket_m').is(":visible") === false && $('.mat_is_m').is(":visible") === false){
            $("#delvdate").val('');
            $(".input").slideDown();
            $(".download").slideUp();
            $('.docket_n').show();
            $('.docket_m').show();
        }; 
    }
    
    /*
     * delete acceptance code
     */
    $(".tbl_acceptance").on("click", ".delete_code_acceptance", function(event){
        event.preventDefault();
        var id = $(this).data('id');
        var file = "/ajax/acceptance_delete.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_acceptance");
        var data = "code="+id;
         BootstrapDialog.confirm('Hapus data dengan code : <b>'+id+"</b> ?", function(result){
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
     * edit acceptance code
     */
    $(".tbl_acceptance").on("click", ".edit_code_acceptance", function(event){
        //hide form add code
        $(".add_code_wrapper").hide();
        //show form edit code
        $(".edit_code_wrapper").hide();
        $(".edit_code_wrapper").fadeIn();
        event.preventDefault();
        $("#code_edit").val($(this).data('id'));
        $("#desc_edit").val($(this).data('desc'));
        
        var cate = $(this).data('cate');
        var value=0;
        $("#cate_edit option").each(function()
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
     * form add acceptance code
     */
    $("#frm_add_code_acceptance").submit(function(e){
        e.preventDefault();
        //properties
        var category    = $("#cate");
        var newcode     = $("#newcode");
        var desc        = $("#desc");
        if(category.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Kategori</i> harus diisi";
            print_msg($("#messages"),0,msg);
            category.focus();return false;
        }
        if(newcode.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Kode Acceptance Baru</i> harus diisi";
            print_msg($("#messages"),0,msg);
            newcode.focus();return false;
        }
        if(desc.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Keterangan</i> harus diisi";
            print_msg($("#messages"),0,msg);
            desc.focus();return false;
        }
        
        var file = "/ajax/acceptance_new_code.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_acceptance");
        var data = "cate="+category.val()+"&newcode="+newcode.val()+"&desc="+desc.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
    });
    
    /*
     * form edit acceptance code
     */
    $("#frm_edit_code_acceptance").submit(function(e){
        e.preventDefault();
        //properties
        var category    = $("#cate_edit");
        var code        = $("#code_edit");
        var desc        = $("#desc_edit");
        if(category.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Kategori</i> harus diisi";
            print_msg($("#messages"),0,msg);
            category.focus();return false;
        }
        if(code.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Kode Acceptance Baru</i> harus diisi";
            print_msg($("#messages"),0,msg);
            code.focus();return false;
        }
        if(desc.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Keterangan</i> harus diisi";
            print_msg($("#messages"),0,msg);
            desc.focus();return false;
        }
        
        var file = "/ajax/acceptance_edit_code.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_acceptance");
        var data = "cate="+category.val()+"&code="+code.val()+"&desc="+desc.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
    });
    
    /*
     * form add code BOM
     */
    $("#frm_add_code_bom").submit(function(e){
        e.preventDefault();
        //properties
        var code        = $("#codeversion");
        var nameversion = $("#nameversion");
        var desc        = $("#desc");
        if(code.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Code Version</i> harus diisi";
            print_msg($("#messages"),0,msg);
            code.focus();return false;
        }
        if(nameversion.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Name Version</i> harus diisi";
            print_msg($("#messages"),0,msg);
            nameversion.focus();return false;
        }
        if(desc.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Keterangan</i> harus diisi";
            print_msg($("#messages"),0,msg);
            desc.focus();return false;
        }
        var file = "/ajax/bom_new_name.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_bom");
        var data = "code="+code.val()+"&nameversion="+nameversion.val()+"&desc="+desc.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
    });
    /*
     * klik edit bom code
     */
    $(".tbl_bom").on("click", ".edit_code_bom", function(event){
        //hide form add code
        $(".add_code_var_bom").hide();
        $(".edit_code_var_bom").hide();
        $(".edit_code_var_bom").fadeIn();
        event.preventDefault();
        $("#code").val($(this).data('id'));
        $("#name_edit_bom").val($(this).data('cate'));
        $("#desc_edit").val($(this).data('desc'));
        
      });
    /*
     * Form Edit Kode Bom
     */
    $("#frm_edit_code_bom").submit(function(e){
        e.preventDefault();
        //properties
        var code    = $("#code");
        var nameversion        = $("#name_edit_bom");
        var desc        = $("#desc_edit");
        if(code.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Id Bom</i> harus diisi";
            print_msg($("#messages"),0,msg);
            code.focus();return false;
        }
        if(nameversion.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Nama Baru</i> harus diisi";
            print_msg($("#messages"),0,msg);
            name_edit_bom.focus();return false;
        }
        if(desc.val() === ''){
            var msg = "<b>ALERT!</b><br/> Kolom <i>Keterangan</i> harus diisi";
            print_msg($("#messages"),0,msg);
            desc.focus();return false;
        }
        
        var file = "/ajax/bom_edit_code.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_bom");
        var data = "code="+code.val()+"&nameversion="+nameversion.val()+"&desc="+desc.val();
        process_ajax(base_url,file,data,"refresh",loading_object,msg_object,frm_object);
    });
    
    /*
     * delete BOM code
     */
    $(".tbl_bom").on("click", ".delete_code_bom", function(event){
        event.preventDefault();
        var id = $(this).data('id');
        var file = "/ajax/bom_delete.php";
        var loading_object = $(".loading");
        var msg_object = $("#messages");
        var frm_object = $("#frm_add_code_bom");
        var data = "code="+id;
         BootstrapDialog.confirm('Hapus data dengan code : <b>'+id+"</b> ?", function(result){
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
     * data table
     */
    $('#example').DataTable();
    
    /*
     * angka keydown
     */
    $(".angka").keydown(function (e){
        // Allow: backspace, delete, tab, escape, enter and .
        allow_number_only(e);
    });
    
    /*
     * allows number only
     */
    function allow_number_only(e){
    // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode === 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    }
    
    
    
});


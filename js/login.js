/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var msg = $("#msg");
var loading = $(".loading");
$(document).ready(function(){
    var base_url = location.origin+"/mis/";
//   var base_url = "http://localhost/mis/"
    $("#login").submit(function(e){
        e.preventDefault();
        var id      = $("#userid");
        var pass    = $("#pass");
        
        if(id.val() === ""){
            window.msg.hide();
            window.msg.empty();
            window.msg.append("Field <b>ID User</b> diisi");window.msg.fadeIn();
            id.focus();
            return false;
        }
        if(pass.val() === ""){
            window.msg.hide();
            window.msg.empty();
            window.msg.append("Field Password harus diisi");window.msg.fadeIn();
            pass.focus();
            return false;
        }
        var data = "iduser="+id.val()+"&pass="+pass.val();
        window.loading.show();
        jQuery.ajax({
            type: "POST", // HTTP method POST or GET
            url: base_url+"act/login_act.php", //Where to make Ajax calls
            dataType:"text", // Data type, HTML, json etc.
            data:data, //Form variables
            success:function(response){
                var obj = jQuery.parseJSON(response);
                if(obj.status === 1){
                    window.msg.hide();
                    window.msg.empty();
                    window.msg.append(obj.msg);
                    window.msg.removeClass("alert-warning alert-info alert-danger").addClass("alert-success");
                    window.msg.fadeIn();
                    $("#login").fadeOut();
                    window.setTimeout(function(){
                        window.location.href = base_url+"index.php"
                    }, 1500);
                    window.loading.hide();
                }
                else if(obj.status === 0){
                    window.msg.hide();
                    window.msg.empty();
                    window.msg.append(obj.msg);
                    window.msg.removeClass("alert-warning alert-info alert-success").addClass("alert-danger");
                    window.msg.fadeIn();
                    window.loading.hide();
                }
            },
            error:function (xhr, ajaxOptions, thrownError){
                window.loading.hide();
                alert(thrownError);
            }
        });
    });
});
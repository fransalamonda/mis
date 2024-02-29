/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var base_url = "http://localhost/mis/";
                
$(document).ready(function(){
    var msg             = $("#messages");
    var msg_content     = "";
    var cur_url         = window.location;
    $(".delete").click(function(e){
        e.preventDefault();
        var id_user = $(this).data("id");
        var r = confirm("Anda yakin akan menghapus data \""+id_user+"\" ?")
        if(r === true){
            
            var myData = "id_user="+id_user;

            $("div#loading").show(); //hide loading image
            $("div#bg-loading").show();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: window.base_url+"act/user_delete.php", //Where to make Ajax calls
                dataType:"text", // Data type, HTML, json etc.
                data:myData, //Form variables
                success:function(response){
                    var obj = jQuery.parseJSON(response);
                    if(obj.status === 1){
                        msg.hide();
                        msg.empty();
                        msg_content   =   "<b>Success!</b> "+obj.msg;
                        msg.append(msg_content);


                        msg.removeClass("alert-warning alert-info alert-danger").addClass("alert-success");
                        msg.fadeIn();
                        $("div#loading").hide(); //hide loading image
                        $("div#bg-loading").hide();
                        setTimeout(function(){
                            window.location = cur_url;
                        }, 3000); 
                    }
                    else if(obj.status === 0){
                        msg.hide();
                        msg.empty();
                        msg_content   =   "<b>Alert!</b> "+obj.msg;
                        msg.append(msg_content);
                        msg.removeClass("alert-warning alert-info alert-success").addClass("alert-danger");
                        msg.fadeIn();
                        $("div#loading").hide(); //hide loading image
                        $("div#bg-loading").hide();
                    }
                    else{
                        alert("a");
                        console.log("sini");
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("div#loading").hide(); //hide loading image
                    $("div#bg-loading").hide();
                    alert(thrownError);
                }
            });
        }
    });
    $(".reset").click(function(e){
        e.preventDefault();
        var id_user = $(this).data("id");
        var r = confirm("Anda yakin akan mereset password user \""+id_user+"\" ?")
        if(r === true){
            
            var myData = "id_user="+id_user;

            $("div#loading").show(); //hide loading image
            $("div#bg-loading").show();
            jQuery.ajax({
                type: "POST", // HTTP method POST or GET
                url: window.base_url+"act/user_reset.php", //Where to make Ajax calls
                dataType:"text", // Data type, HTML, json etc.
                data:myData, //Form variables
                success:function(response){
                    var obj = jQuery.parseJSON(response);
                    if(obj.status === 1){
                        msg.hide();
                        msg.empty();
                        msg_content   =   "<b>Success!</b> "+obj.msg;
                        msg.append(msg_content);


                        msg.removeClass("alert-warning alert-info alert-danger").addClass("alert-success");
                        msg.fadeIn();
                        $("div#loading").hide(); //hide loading image
                        $("div#bg-loading").hide();
                        setTimeout(function(){
                            msg.fadeOut();
                        }, 3000); 
                    }
                    else if(obj.status === 0){
                        msg.hide();
                        msg.empty();
                        msg_content   =   "<b>Alert!</b> "+obj.msg;
                        msg.append(msg_content);
                        msg.removeClass("alert-warning alert-info alert-success").addClass("alert-danger");
                        msg.fadeIn();
                        $("div#loading").hide(); //hide loading image
                        $("div#bg-loading").hide();
                    }
                    else{
                        alert("a");
                        console.log("sini");
                    }
                },
                error:function (xhr, ajaxOptions, thrownError){
                    $("div#loading").hide(); //hide loading image
                    $("div#bg-loading").hide();
                    alert(thrownError);
                }
            });
        }
    });
    $('#dataTables-example').dataTable();
});
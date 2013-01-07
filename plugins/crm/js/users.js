$(document).ready(function () {

   

    function EditUser() {
       this.the_data_o = {};
        this.the_url = "http://localhost/ces_crm/plugins/crm/bin/functions.php";

        
        this.test = function(what){
        	alert(what);	
        }
        
        this.call_user_form = function(user_id){
        	
         this.the_data_o["id"] = user_id;
         var new_row_data_obj = JSON.stringify(this.the_data_o);
         alert(new_row_data_obj);
         $.ajax({
                type: "POST",
                url: this.the_url+"?action=call_user_form",
                data: {
                    json: new_row_data_obj
                },
                success: function (data) {
                	//alert(data);
                    if (data) {
                        var obj = jQuery.parseJSON(data);
                        if (typeof (obj.new_id) == "number" && obj.new_id > 1) {
                            $("#new_row_form textarea").val("");
                            $("#table tbody").append(obj.new_row_content);
                        }
                    }

                }
            });

            return false;	
        	
        }
       

        
        
        

    }

    var editUser = new EditUser();


    $('.edit_user').live('click', function () {
       //alert($(this).attr('id'));
        editUser.call_user_form($(this).attr('id'));
    });
   




});
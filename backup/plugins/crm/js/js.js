$(document).ready(function () {

    
    function cellsaver() {
        var cell_arr = [];
        var the_data_o = {};
        this.the_url = "http://localhost/ces_crm/plugins/crm/bin/functions.php";

        this.add_cells_to_arr = function (what) {
            cell_arr.push(what);
        };
        
        this.shout = function () {
            jQuery.each(cell_arr, function (index, item) {
                var the_value = $('#' + item).val();
                //alert("the cell: "+item+";;; the value:"+the_value);

                if (the_data_o[item] !== undefined) {
                    //alert(item);
                    if (!the_data_o[item].push) {
                        the_data_o[item] = [the_data_o[item]];
                    }
                    // the_data_o[item].push(the_value);
                } else {
                    the_data_o[item] = the_value;
                }
                //alert(o[item]);	
            });

            //clear the array

            the_data_o["table"] = $('#table').attr("class");
            var the_data_obj = JSON.stringify(the_data_o);
            this.reset_arr();
            this.send_to_server(the_data_obj);
        };
        this.reset_arr = function () {
            cell_arr = [];
            the_data_o = {};
        };
        this.insert_new_row = function () {
            var new_row_data_obj = $('#new_row_form').serializeObject();
            new_row_data_obj["table"] = $('#table').attr("class");
            var new_row_data_obj = JSON.stringify(new_row_data_obj);
            this.new_row_to_server(new_row_data_obj);
            return false;
        };
        $.fn.serializeObject = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };

        this.send_to_server = function (the_data) {
           // alert(the_data);
            $.ajax({
                type: "POST",
                url: this.the_url+"?action=update",
                data: {
                    json: the_data
                },
                success: function (data) {
                   // alert(data);
                    if (data == 1) {
                        $("#controlpanel_message").html("Content Saved");
                        $('#controlpanel_message').fadeIn();
                        setTimeout("jQuery('#controlpanel_message').fadeOut('slow');", 2000);
                        //	$("#controlpanel_message").html("");	
                    }

                }
            });

            return false;

        }
        this.new_row_to_server = function (the_data) {
            //alert(the_data);
            $.ajax({
                type: "POST",
                url: this.the_url+"?action=insert",
                data: {
                    json: the_data
                },
                success: function (data) {
                	//alert(data);
                    if (data) {
                        var obj = jQuery.parseJSON(data);
                        if (typeof (obj.new_id) == "number" && obj.new_id > 1) {
                            $("#new_row_form textarea").val("");
                            $("#table tbody").prepend(obj.new_row_content);
                        }
                    }

                }
            });

            return false;

        }
        this.delete_row = function (what) {
          
           var delete_data_o = {};
           delete_data_o["id"] = what;
           delete_data_o["table"] = $('#table').attr("class");
           var the_data_del = JSON.stringify(delete_data_o);
           
           $.ajax({
                type: "POST",
                url: this.the_url+"?action=delete",
                data: {
                    json: the_data_del
                },
                success: function (data) {
                	if (data) {
                        var obj = jQuery.parseJSON(data);
                        if (typeof (obj.new_id) == "string" && (obj.result)==true) {
                            var del_id =obj.new_id;
                           $("#"+del_id).parents("tr").hide();
                        }
                    }

                }
            });

            return false;
        };
        this.restore_row = function (what) {
          
           var delete_data_o = {};
           delete_data_o["id"] = what;
           delete_data_o["table"] = $('#table').attr("class");
           var the_data_del = JSON.stringify(delete_data_o);
           
           $.ajax({
                type: "POST",
                url: this.the_url+"?action=restore",
                data: {
                    json: the_data_del
                },
                success: function (data) {
                	if (data) {
                        var obj = jQuery.parseJSON(data);
                        if (typeof (obj.new_id) == "string" && (obj.result)==true) {
                            var del_id =obj.new_id;
                           $("#"+del_id).parents("tr").removeClass("deleted_row");
                           $("#"+del_id).attr('class', 'delete');
                           $("#"+del_id).val("Delete");
                        }
                    }

                }
            });

            return false;
        };

    }

    var oldBlue = new cellsaver();


    $('#table textarea').live('click', function () {
        oldBlue.add_cells_to_arr($(this).attr('id'));
    });
    $('#save_cells').live('click', function () {
        oldBlue.shout();
    });
    $('#add_row').live('click', function () {
        oldBlue.insert_new_row();
        return false;
    });
     $('.delete').live('click', function () {
        oldBlue.delete_row($(this).attr('id'));
        return false;
    });
     $('.restore').live('click', function () {
        oldBlue.restore_row($(this).attr('id'));
        return false;
    });
    $('#dr_form select').live('change', function () {
      $('#dr_form').submit();
    });
    setInterval(function() {
	     oldBlue.shout();
	}, 5000);




});
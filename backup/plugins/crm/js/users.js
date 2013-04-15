$(document).ready(function() {

	function EditUser() {
		this.the_data_o = {};
		this.the_url = "http://localhost/ces_crm/plugins/crm/bin/user_functions.php";

		this.test = function(what) {
			alert(what);
		}
		this.CreateUserForm = function() {
			console.log("CreateUserForm");
			$.ajax({
				type : "POST",
				url : this.the_url + "?action=crate_new_user_form",
				
				success : function(data) {
					//alert(data);
					if (data) {
						var obj = jQuery.parseJSON(data);
						$("#UserFormArea").html(obj);
					}

				}
			});
		}

		this.call_user_form = function(user_id) {

			this.the_data_o["id"] = user_id;
			var new_row_data_obj = JSON.stringify(this.the_data_o);
			console.log(new_row_data_obj);
			$.ajax({
				type : "POST",
				url : this.the_url + "?action=call_user_form",
				data : {
					json : new_row_data_obj
				},
				success : function(data) {
					//alert(data);
					if (data) {
						var obj = jQuery.parseJSON(data);
						console.log(obj);
						$("#UserFormArea").html(obj);
					}

				}
			});

			return false;

		}
	}

	var editUser = new EditUser();
	$('.edit_user').live('click', function() {
		//alert($(this).attr('id'));
		editUser.call_user_form($(this).attr('id'));
	});
	$('#CreateNewUserFormButton').live('click', function() {
		editUser.CreateUserForm();
	});

}); 
var __mysys_coa_ent = new __mysys_coa_ent();
function __mysys_coa_ent() {  
	const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

	this.__coa_saving = function() { 
		'use strict' 
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.querySelectorAll('.mycoa-validation')
		// Loop over them and prevent submission
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}
				try {
					event.preventDefault();
					event.stopPropagation();

					var account_id = document.getElementById("account_id");
					var account_code = document.getElementById("account_code");
					var account_name = document.getElementById("account_name");
					var account_type = document.getElementById("account_type");
					var parent_code = document.getElementById("parent_code");
					var is_active = document.getElementById("is_active");

					var mparam = { 
						account_id: account_id.value,
						account_code: account_code.value,
						account_name: account_name.value,
						account_type: account_type.value,
						parent_code: parent_code.value,
						is_active: is_active.value,
						meaction: 'COA-SAVE'
					}

					jQuery.ajax({ // default declaration of ajax parameters
						type: "POST",
						url: mesiteurl + 'mycoa',
						context: document.body,
						data: eval(mparam),
						global: false,
						cache: false,
						success: function(data) { //display html using divID
							jQuery('.me-mycoa-outp-msg').html(data);
							return false;
						},
						error: function(xhr, status, error) { // display global error on the menu function
							//__mysys_apps.mybs_simple_toast('memsgtoastcont','metoastmsglang','align-items-center text-bg-danger border-0','Hello, Error Loading Page [TRXMGT-AP-ITEM-TAXDED-ENT]' + error);
							toastr.error('[mycoa-ENT', "Hello, Error Loading Page..." + error, {
							closeButton: true,
							});
							return false;
						} 
					}); 

					console.log("AJAX URL:", mesiteurl + 'mycoa');
				} catch(err) { 
					alert(err.message)
					return false;
				} //end try 
			}, false)
		}); //end forEach		
	}; //

	

}; //end main

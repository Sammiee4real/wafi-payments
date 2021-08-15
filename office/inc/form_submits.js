submit_form('add_project_report', 'cmd_add_project_report', 'Add Project Report', true, 'add_project_project', 'Adding project...', 'project Added', 'add_project_project');
submit_form('add_admin_form', 'cmd_add_admin', 'Add Admin', true, 'add_fk_admin', 'Adding Admin...', 'Admin Added Successfully', 'add_fk_admin');
submit_form('add_cooperative', 'cmd_add_cooperative', 'Add Cooperative', true, 'add_cooperative', 'Adding cooperative...', 'Cooperative Added', 'add_cooperative');
submit_form('add_bk_admin', 'cmd_add_bank_admin', 'Add Bank Admin', true, 'add_bank_admin', 'Adding Admin...', 'Admin Added Successfully', 'add_bank_admin');
submit_form('add_project', 'cmd_add_project', 'Add project', true, 'add_project', 'Adding project...', 'Project Added Successfully', 'add_project');
submit_form('add_user_group', 'cmd_add_user_group', 'Add Member Group', true, 'add_user_group', 'Adding Member Group...', 'Member Group Added Successfully', 'add_user_group');
submit_form('add_zoom_meeting', 'cmd_add_zoom_meeting', 'Add Zoom Meeting', true, 'add_zoom_meeting', 'Adding zoom meeting...', 'Zoom Meeting Added Successfully', 'add_zoom_meeting');
submit_form('edit_cooperative_form', 'cmd_edit_cooperative', 'Edit Cooperative', true, 'edit_cooperative', 'Editing cooperative...', 'Cooperative was Successfully edited', 'manage_cooperatives');
submit_form('edit_project_form', 'cmd_edit_project', 'Edit Project', true, 'edit_project', 'Editing project...', 'Project was Successfully edited', 'view_projects');
submit_form('edit_farm_form', 'cmd_edit_farm', 'Edit Farm', true, 'edit_farm', 'Editing farm...', 'Farm was Successfully edited', 'manage_farms');
submit_form('assign_users_to_cooperative_form', 'cmd_assign_users_to_cooperative', 'Assign Member(s) to Cooperative', true, 'assign_users_to_cooperative', 'Assigning Member(s) to cooperative...', 'Member(s) successfully assigned to Cooperative', 'assign_users_to_cooperative');
submit_form('unassign_users_to_cooperative_form', 'cmd_unassign_users_to_cooperative', 'Unassign Member(s) from Cooperative', true, 'unassign_users_to_cooperative', 'Unassigning member(s) to cooperative...', 'Member(s) successfully unassigned from Cooperative', 'unassign_users_to_cooperative');
submit_form('assign_project_to_cooperative_form', 'cmd_assign_project_to_cooperative', 'Assign Project and Farm to Cooperative', true, 'assign_project_to_cooperative', 'Assigning Project and Farm to cooperative...', 'Project(s)  successfully assigned to Cooperative', 'assign_project_to_cooperative');
submit_form('unassign_project_from_cooperative_form', 'cmd_unassign_project_from_cooperative', 'Unssign Project from a Cooperative', true, 'unassign_project_from_cooperative', 'Unassigning Project from a cooperative...', 'Project was successfully unassigned from Cooperative', 'unassign_project_from_cooperative');
submit_form('assign_farms_to_project_form', 'cmd_assign_farms_to_project', 'Assign Farms to Project ', true, 'assign_farms_to_project', 'Assigning Farm(s) to Project...', 'Farm(s) was successfully assigned to Project', 'assign_farms_to_project');
submit_form('unassign_farms_from_project_form', 'cmd_unassign_farms_from_project', 'Unassign Farms', true, 'unassign_farms_from_project', 'Unassigning Farm(s)...', 'Farm(s) was successfully unassigned', 'unassign_farms_from_project');
submit_form('add_farm_form', 'cmd_add_farm', 'Add Farm', true, 'add_farm', 'Adding a farm...', 'Farm was added successfully', 'add_farm');
submit_form('update_user_form', 'cmd_update_user', 'Update Member', true, 'edit_member', 'Updating  a member record...', 'Member record was updated successfully', 'edit_member');
submit_form('update_user_coop_form', 'cmd_update_user_coop', 'Update Member Cooperative', true, 'update_user_coop', 'Updating  a member cooperative record...', 'Member cooperative record was updated successfully', 'edit_member');
submit_form('update_my_profile_form', 'cmd_update_my_profile', 'Update My Profile', true, 'update_my_profile', 'Updating your profile...', 'Your profile was updated successfully', 'view_profile');
submit_form('update_admin_form', 'cmd_update_admin', 'Update Admin', true, 'edit_admin', 'Updating Admin profile...', 'Admin profile was updated successfully', 'view_fk_admin');
submit_form('update_bank_form', 'cmd_update_bank', 'Update Bank Admin', true, 'edit_bank', 'Updating Bank profile...', 'Bank profile was updated successfully', 'view_bank_admin');



// function submit_form(formName, btnName, btnOValue, btnInput=false, url, bMsg, sMsg, redirectTo)
// {
//     $('#'+btnName).click(function (e) {
//         e.preventDefault();
//         $.ajax({
//             url:"ajax/"+url+".php",
//             method: "POST",
//             data:$('#'+formName).serialize(),
//             beforeSend: function(){
//                 $('#'+btnName).attr('disabled', true);
//                 if(btnInput == false) $('#'+btnName).html(bMsg);
//                 if(btnInput == true) $('#'+btnName).val(bMsg);
//             },
//             success:function(data){
//                 if(data == 200){
//                     toastr.success(sMsg, "Success!");
//                     setTimeout( function(){ 
//                         window.location.href = redirectTo; 
//                     }, 3000);
//                 }
//                 else{
//                     toastr.error(data, "Caution!");
//                 }
//                 $('#'+btnName).attr('disabled', false);
//                 if(btnInput == false) $('#'+btnName).html(btnOValue);
//                 if(btnInput == true) $('#'+btnName).val(btnOValue);
//             }
//         });     
//     });
// }


jQuery(function(){
    var box = document.getElementsByClassName("team-members-box"), btn_crud = document.getElementsByClassName("btn-crud"), ch_act = document.getElementsByClassName("choose-actions"), m = document.getElementsByClassName('modal');
    var teamMember = {
        init: function() {
            model.init();
            $(document).on("click", ".team-members-box .choose-actions .btn_edit", function(e) {
                url=jQuery(this).data('url');
                e.preventDefault();
                jQuery('.team-modal-title').html('Edit Team');
                var updateUrl = url.slice(0,-5);
                jQuery(document).load(url,function(getData,status,xhr){
                    getData=jQuery.parseJSON(getData);
                    jQuery("#department_id").empty();
                    jQuery.each(getData.departments,function(key,value){
                        jQuery("#department_id").append('<option value='+key+'>'+value+'</option>');
                    });
                    jQuery("#teamlead_id").empty();
                    jQuery.each(getData.teamLeads,function(key,value){
                        jQuery("#teamlead_id").append('<option value='+key+'>'+value+'</option>');
                    });
                    jQuery("#member_id").empty();
                    jQuery.each(getData.allMembers,function(key,value){
                        jQuery("#member_id").append('<option value='+key+'>'+value+'</option>');
                    });
                    jQuery('select[name=teamlead_id]').val(getData.team.teamlead_id);
                    jQuery('.selectpicker').selectpicker('refresh');
                    jQuery('select[name=department_id]').val(getData.team.department_id);
                    jQuery('.selectpicker').selectpicker('refresh');
                    jQuery('#member_id').val(getData.teamMembers);
                    jQuery('.selectpicker').selectpicker('refresh');
                    jQuery('#demo-form2').attr('method','PUT');
                    jQuery('#demo-form2').attr('action',updateUrl);
                }).modal('show');
                return false;  
            });
            jQuery(document).on('click', '.team-members-box .choose-actions a.btn_delete', function(e) {
                e.preventDefault(); // does not go through with the link.
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });
                swal({
                    title: 'Are you Sure?',
                    text: 'You won\'t be able to revert this!',
                    type: 'warning',
                    timer: 7000,
                    showCancelButton: true
                }).then((result)=>{
                    if (result.value) {
                        jQuery('#pageloader').show();
                        var $this = jQuery(this);
                        jQuery.ajax({
                            type: $this.data('method'),
                            url: $this.data('url')
                        }).done(function (test) {
                            if(test.success){
                                jQuery('#pageloader').hide();
                                swal("Deleted!", "Your record has been deleted.", "success").then(function(){
                                    window.location.reload();    
                                });
                            }
                        });
                    } else if (result.dismiss === swal.DismissReason.cancel) {
                    swal('Cancelled', 'Your record is safe', 'info');
                    }
                });
            });
            jQuery('#team-members-modal form').on('submit',function(){
                teamMember.fire(jQuery(this));
            });
        },
        CRUD: function () {
            jQuery('#team-members-modal .btn-crud').off().on("click",function(e){
                teamMember.fire(jQuery('#team-members-modal form'));
            });
        },
        refresh: function(n) {
            jQuery(box).load(document.URL + ' .team-members-box');
        },
        fire: function(f) {
            jQuery('#pageloader').show();
            var _f = f;
            var ajax = {
                fire: function(){
                    jQuery('#team-members-modal').find('.error').html('');
                    $.ajax({
                        type: _f.attr('method'),
                        url: _f.attr('action'),
                        data: _f.serialize(),
                        success: function(response) {
                            response.fail ? ajax.error(response) : ajax.success(response);
                        }        
                    });
                },
                success: function(response){
                    window.location.reload();
                    model.hide();
                    teamMember.refresh();   
                },
                error: function(e){
                    if(e.fail){
                        jQuery('.department_id_error').html(e.deptExistsError);
                        jQuery('.teamlead_id_error').html(e.teamLeadExistsError);
                         jQuery.each(e.errors, function( index, value ) {
                             var errorDiv = '.'+index+'_error';
                             jQuery(errorDiv).addClass('required');
                            jQuery(errorDiv).empty().html(value);
                        });
                    }
                }
            }
            ajax.fire();
        }
    }
    var model = {
        init: function(){
            jQuery(m).on('shown.bs.modal',function(){
                teamMember.CRUD();
            });
            jQuery(m).on('hidden.bs.modal', function(){
                   window.location.reload();
                $(this).find('form')[0].reset();
                 jQuery('.team-modal-title').html('Add Team');
                $(".selectpicker").selectpicker('val','0');
            });
        },
        hide: function(){
            jQuery(m).modal('hide');
            window.location.reload();
        }
    }
    teamMember.init();
});
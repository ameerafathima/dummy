$( function() 
{
    $(document).on('click','#submitbutton',function()
    {
        $('#validate').validate({ // initialize the plugin
            rules: 
            {
                firstname: "required",
                lastname: "required",
                password:"required",
                confirmpassword:
                {
                    required:true,
                    equalTo:"#pass"
                },
                email:
                {
                    required:true,
                    email:true
                }
            },
            messages:
            {
                firstname:"please enter first name",
                lastname: "please enter last name",
                email: "please enter valid email",
                password:"please enter password",
                confirmpassword:"please confirm password"
            },
            submitHandler: function(form,e) 
            {
                e.preventDefault();
                var url = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/getuserdata";
                console.log(url);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#validate").serialize(), // serializes the form's elements.
                    success: function(msg)
                    {
                        if($.trim(msg) =='h')
                        { 
                            alert('success'); 
                            window.location.href = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/login";
                        }
                        else
                        {
                            alert('Failed');
                        }
                    }
                });
            }
        })
    }),
        
    $(document).on('click','#b3',function()
    {
        $('#login').validate({ // initialize the plugin
            rules: 
            {
                user_email:
                {
                    required:true,
                    email:true
                },
                user_password:"required"
            },
            messages:
            {
                user_email:
                {
                    required:"Please enter email",
                    email:"Please enter valid email"
                },
                user_password:"Please enter password"
            },
            submitHandler: function(form,e)
            {
                e.preventDefault();
                var url = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/login_user";
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#login").serialize(), // serializes the form's elements.
                    success: function(msg)
                    {
                        if($.trim(msg) =="success")
                        { 
                            // alert('success'); 
                            swal({
                                title:'success'
                            }).then (function(){
                            window.location.href = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/home";
                            })
                        }
                        else
                        {
                            swal({
                                title:'failed'
                            });
                            // alert('failed');
                        }
                    }
                });
            }
        })
    }),

    $(document).on('click','#submitaddproject',function()
    {
        $('#form').validate({ 
            rules: 
            {
                projectname:"required",
                projectstatus:"required",
                projectrating:"required",
                projecthead:"required",
                projectdate:"required",
            },
            messages:
            {
                projectname:"please enter projectname",
                projectstatus:"please enter project status",
                projectrating:"please enterproject rating ",
                projecthead:"please enter projecthead",
                projectdate:"please enter projectdate",
            },
        })
    }),

    $(document).on('click','#updateproject',function()
    {
        $('#update').validate({ 
            rules: 
            {
                projectname:"required",
                projectstatus:"required",
                projectrating:"required",
                projecthead:"required",
                projectdate:"required",
            },
            messages:
            {
                projectname:"please enter projectname",
                projectstatus:"please enter project status",
                projectrating:"please enterproject rating ",
                projecthead:"please enter projecthead",
                projectdate:"please enter projectdate",
            },
            submitHandler: function(form,e) 
            {
                e.preventDefault();
                var url = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/updateproject";
                console.log(url);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#update").serialize(), // serializes the form's elements.
                    success: function(msg)
                    {
                        console.log(msg);
                        if($.trim(msg) =='u')
                        { 
                            // alert('success');
                            swal({
                                title:'success'
                            }).then (function(){ 
                            window.location.href = "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/project_list";
                            })
                        }
                        else
                        {
                            // alert('Failed');
                            swal({
                                title:'failed'
                            });
                        }
                    }
                });
            }
        })
    })
   
    $("#countryid").change(function() 
    {
        var country_id = {"country_id" : $('#countryid').val()};
        console.log("reshma");
        console.log(country_id);
        $.ajax({
            type: "POST",
            data: country_id,
            url: "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/ajax_state_list/",
            success: function(data)
            {
                $('#state').html(data);
            }
        });
    }),

    $("#state").change(function() 
    {
        var state_id = {"state_id" : $('#state').val()};
        console.log("reshma1");
        console.log(state_id);
        $.ajax({
            type: "POST",
            data: state_id,
            url: "http://192.168.20.245/codeigniterproject/newproject/index.php/welcome/ajax_city_list/",
            success: function(data)
            {
                $('#city').html(data);
            }
        });
    }),

    $(document).ready(function () 
    {
        $('#table').dataTable();
        $(".chzn-select").chosen();
    });  
})
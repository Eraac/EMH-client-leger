var selectedStatus = new Array();
var selectedSubmit = new Array();
var selectedValid = new Array();

function removeSelectedStatus(id)
{
    for(var i = 0; i < selectedStatus.length; i++)
    {
        if(selectedStatus[i] === id)
        {
            delete selectedStatus[i];
        }
    }
}

function removeSelectedSubmit(id)
{
    for(var i = 0; i < selectedSubmit.length; i++)
    {
        if(selectedSubmit[i] === id)
        {
            delete selectedSubmit[i];
        }
    }   
}

function removeSelectedValid(id)
{
    for(var i = 0; i < selectedValid.length; i++)
    {
        if(selectedValid[i] === id)
        {
            delete selectedValid[i];
        }
    }
}

function searchRegistration()
{
    var url = window.location.href;
    
    // TODO loader
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        data:{
            idStatus : selectedStatus,
            submit: selectedSubmit,
            valid: selectedValid
        },
        success: function (datas){
            console.log(datas);

            $("#listRegistration").html("");

            var response = "";

            if (0 >= datas['registrations'].length)
            {
                $("#listRegistration").html('<tr><td colspan="4">Aucun enregistrement</td></tr>');
            }
            else
            {
                for(i in datas['registrations'])
                {
                    var className = "";
                    var status = "";
                    var date = new Date(datas['registrations'][i]['registrationDate']['date']);

                    var day = (date.getDate() < 10) ? "0" + date.getDate() : date.getDate();
                    var month = (date.getMonth() < 10) ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
                    var hours = (date.getHours() < 10) ? "0" + (date.getHours() + 1) : date.getHours();
                    var minutes = (date.getMinutes() < 10) ? "0" + (date.getMinutes() + 1) : date.getMinutes();

                    var date = day + "-" + month + "-" + date.getFullYear() + " à " + hours + ":" + minutes;

                    //console.log(datas[i]['name']);
                    response += "<tr>";
                    response +=  "<td>" + datas['registrations'][i]['userSubmit']['firstName'] + " " + datas['registrations'][i]['userSubmit']['name'] + "</td>";
                    response +=  "<td>";

                    if (1 === datas['registrations'][i]['status']) {
                        className = "info";
                        status = "En cours";
                    } else if (2 === datas['registrations'][i]['status']) {
                        className = "primary";
                        status = "Validé";
                    } else if (3 === datas['registrations'][i]['status']) {
                        className = "success";
                        status = "Accepté";
                    } else if (4 === datas['registrations'][i]['status']) {
                        className = "danger";
                        status = "Refusé";
                    }

                    response += '<span class="label label-' + className + '">' + status + '</span>\n' + "</td>";

                    response += "</td>";
                    response +=  "<td>" + date + "</td>";
                    response +=  '<td class="text-center"><a href="/app_dev.php/read/' +  datas['registrations'][i]['id'] + '"><span class="glyphicon glyphicon-search"></span></a></td>'; // TODO Changer URL en prod
                    response += "</tr>";
                }
                $("#listRegistration").append(response);
            }

            makePagination(datas['hasNext']);
        }

    });
}

$(document).ready(function() {   
    // On stocke dans selectedTags on stocke toutes les ID des tags
    $('.selectableStatus').each(function() {
            selectedStatus.push($(this).attr('id'));            
        }
    )
    
    $('.selectableSubmit').each(function() {
            selectedSubmit.push($(this).attr('id'));            
        }
    )
    
    $('.selectableValid').each(function() {
            selectedValid.push($(this).attr('id'));            
        }
    )

    $('.selectableStatus').click(function() {
        
        if($(this).hasClass($(this).attr('data-class')))
        {
            $(this).removeClass($(this).attr('data-class'));
            $(this).addClass('label-default');
            removeSelectedStatus($(this).attr('id'));
        }
        else
        {
            $(this).removeClass('label-default');
            $(this).addClass($(this).attr('data-class'));
            selectedStatus.push($(this).attr('id'));
        }
        
        //console.log(selectedStatus);
        searchRegistration();
    });
    
    $('.selectableSubmit').change(function() {
        
        if(!$(this).prop("checked"))
        {
            removeSelectedSubmit($(this).attr('id'));
        }
        else
        {
            selectedSubmit.push($(this).attr('id'));
        }
        
        //console.log(selectedSubmit);
        searchRegistration();
    });
    
    $('.selectableValid').change(function() {
        
        if(!$(this).prop("checked"))
        {
            removeSelectedValid($(this).attr('id'));
        }
        else
        {
            selectedValid.push($(this).attr('id'));
        }
        
        //console.log(selectedValid);
        searchRegistration();
    });

})

function makePagination(hasNext)
{
    $(".page").remove();
    $(".next-page").remove();
    $(".previous-page").remove();

    $(".pagination").append('<li class="previous-page disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>');
    $(".pagination").append('<li class="page active"><a href="#">1 <span class="sr-only">(current)</span></a></li>');

    var disable = hasNext ? '' : 'disabled';

    if (hasNext)
        $(".pagination").append('<li class="page"><a href="/search/registration/2">2</a></li>');

    $(".pagination").append('<li class="' + disable + ' next-page"><a href="/search/registration/2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
}
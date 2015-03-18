var selectedStatus = new Array();
var selectedSubmit = new Array();
var selectedValid = new Array();
var optionChange = false;
var end = false;
var alreadyRequest = false;

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
    if (alreadyRequest)
        return;

    alreadyRequest = true;
    var url = window.location.href;
    var countRegistration = optionChange ? 0 : $("#listRegistration .registrationHIA").length;

    //console.log("Change option : " + optionChange);
    //console.log("Count registration : " + countRegistration);

    var loader = $('#loader');

    loader.addClass("glyphicon glyphicon-refresh rotating");

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        data:{
            idStatus : selectedStatus,
            submit: selectedSubmit,
            valid: selectedValid,
            nbRegistration: countRegistration
        },
        success: function (datas) {
            //console.log(datas);

            //console.log("Change option 2 : " + optionChange);

            if (optionChange) {
                $("#listRegistration").html("");
                end = false;
                //console.log("Suppression tableau")
            }

            var response = "";

            if (0 >= datas['registrations'].length)
            {
                if (optionChange) {
                    $("#listRegistration").html("<tr><td class='text-center end-table' colspan='4'>Aucun résultat disponible</td></tr>")
                } else {
                    if (0 < countRegistration && !end) {
                        $("#listRegistration").append("<tr><td class='text-center end-table' colspan='4'>Vous êtes à la fin !</td></tr>")
                        end = true;
                    }
                }
            }
            else
            {
                for(i in datas['registrations'])
                {
                    var urlRegistration = Routing.generate('HIAFormReadRegistration', {id: datas['registrations'][i]['id']}, true);

                    var className = "";
                    var status = "";
                    var date = new Date(datas['registrations'][i]['registrationDate']['date']);

                    var day = (date.getDate() < 10) ? "0" + date.getDate() : date.getDate();
                    var month = (date.getMonth() < 10) ? "0" + (date.getMonth() + 1) : date.getMonth() + 1;
                    var hours = (date.getHours() < 10) ? "0" + (date.getHours() + 1) : date.getHours();
                    var minutes = (date.getMinutes() < 10) ? "0" + (date.getMinutes() + 1) : date.getMinutes();

                    var date = day + "-" + month + "-" + date.getFullYear() + " à " + hours + ":" + minutes;

                    //console.log(datas[i]['name']);
                    response += "<tr class=\"registrationHIA\">";
                    response +=  "<td>" + datas['registrations'][i]['userSubmit']['firstName'] + " " + datas['registrations'][i]['userSubmit']['name'] + "</td>";
                    response +=  "<td>";

                    if (1 === datas['registrations'][i]['status']) {
                        className = "info";
                        status = "Nouveau";
                    } else if (2 === datas['registrations'][i]['status']) {
                        className = "info";
                        status = "En cours";
                    } else if (3 === datas['registrations'][i]['status']) {
                        className = "primary";
                        status = "Validé";
                    } else if (4 === datas['registrations'][i]['status']) {
                        className = "success";
                        status = "Accepté";
                    } else if (5 === datas['registrations'][i]['status']) {
                        className = "danger";
                        status = "Refusé";
                    }

                    response += '<span class="label label-' + className + '">' + status + '</span>\n' + "</td>";

                    response += "</td>";
                    response +=  "<td>" + date + "</td>";
                    response +=  '<td class="text-center"><a href="' +  urlRegistration + '"><span class="glyphicon glyphicon-search"></span></a></td>';
                    response += "</tr>";
                }
                $("#listRegistration").append(response);
            }

            optionChange = false;

            loader.removeClass("glyphicon glyphicon-refresh rotating");
        },
        error: function() {
            $("#listRegistration").html("<tr><td class='text-center end-table red' colspan='4'>Une erreur est survénu</td></tr>");

            loader.removeClass("glyphicon glyphicon-refresh rotating");
        },
        complete: function() {
            alreadyRequest = false;
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

        if (alreadyRequest)
            return;

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

        optionChange = true;
        
        //console.log(selectedStatus);
        searchRegistration();
    });
    
    $('.selectableSubmit').change(function() {

        if (alreadyRequest)
            return;

        if(!$(this).prop("checked"))
        {
            removeSelectedSubmit($(this).attr('id'));
        }
        else
        {
            selectedSubmit.push($(this).attr('id'));
        }

        optionChange = true;

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

        optionChange = true;
        
        //console.log(selectedValid);
        searchRegistration();
    });

    $('#moreRegistration').click(function() {

        if (alreadyRequest)
            return;

        searchRegistration();
        optionChange = false;
    })

    searchRegistration();

})

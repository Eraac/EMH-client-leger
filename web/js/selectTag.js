var selectedTags = new Array();
var changeOption = false;
var end = false;

function removeSelectedTag(id)
{
    for(var i = 0; i < selectedTags.length; i++)
    {
        if(selectedTags[i] == id)
        {
            delete selectedTags[i];
        }
    }
}

function searchForm()
{
    var textToSearch = $("#formName").val();
    var url = window.location.href;
    var countForms = changeOption ? 0 : $("#listForm .formHIA").length;

    //console.log("Change option : " + changeOption);
    //console.log("Count form : " + countForms);


    var loader = $('#loader');

    loader.addClass("glyphicon glyphicon-refresh rotating");

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: url,
        data:{
            idTag : selectedTags,
            formName : textToSearch,
            nbForms: countForms
        },
        success: function (datas) {
            //console.log(datas);

            if (changeOption) {
                $("#listForm").html("");
                end = false;
            }

            var response = "";

            if (0 >= datas['forms'].length)
            {
                if (changeOption) {
                    $("#listForm").html("<tr><td class='text-center end-table' colspan='2'>Aucun résultat disponible</td></tr>")
                } else {
                    if (0 < countForms && !end) {
                        $("#listForm").append("<tr><td class='text-center end-table' colspan='2'>Vous êtes à la fin !</td></tr>")
                        end = true;
                    }
                }
            }
            else
            {
                for(i in datas['forms'])
                {
                    var urlForm = Routing.generate('HIAFormUse', {slug: datas['forms'][i]['slug']}, true);

                    response += "<tr class=\"formHIA\">";
                    response +=  "<td>" + datas['forms'][i]['name'] + "</td>";
                    /*response +=  "<td>";

                    for (y in datas['forms'][i]['tags'])
                    {
                        response += '<span class="label label-info">' + datas['forms'][i]['tags'][y]['name'] + '</span>\n';
                    }

                    response += "</td>";*/
                    response +=  '<td class="text-center"><a href="' +  urlForm + '"><span class="glyphicon glyphicon-search"></span></a></td>'; // TODO Changer URL en prod
                    response += "</tr>";
                }
                $("#listForm").append(response);
            }

            changeOption = false;

            loader.removeClass("glyphicon glyphicon-refresh rotating");
        },
        error: function() {
            $("#listForm").html("<tr><td class='text-center end-table red' colspan='2'>Une erreur est survénu</td></tr>");

            loader.removeClass("glyphicon glyphicon-refresh rotating");
        }

    });
}

$(document).ready(function()
{
    // On stocke dans selectedTags on stocke toutes les ID des tags
    $('.selectableTag').each(function() {
            selectedTags.push($(this).attr('id'));
        }
    )


    $('.selectableTag').click(function()
    {
        if($(this).hasClass('label-primary'))
        {
            $(this).removeClass('label-primary');
            $(this).addClass('label-default');
            removeSelectedTag($(this).attr('id'));
        }
        else
        {
            $(this).removeClass('label-default');
            $(this).addClass('label-primary');
            selectedTags.push($(this).attr('id'));
        }
        //console.log(selectedTags);
        changeOption = true;

        searchForm();

        //console.log("Click on tag : " + changeOption);
    });


    $("#btnFormSearch").click(function() {
        changeOption = true;
        searchForm();
    });

    $("#moreForm").click(function() {
        changeOption = false;
        searchForm();
    })

    searchForm();
})

/*
function makePagination(hasNext)
{
    $(".page").remove();
    $(".next-page").remove();
    $(".previous-page").remove();

    $(".pagination").append('<li class="previous-page disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>');
    $(".pagination").append('<li class="page active"><a href="#">1 <span class="sr-only">(current)</span></a></li>');

    var disable = hasNext ? '' : 'disabled';

    $(".pagination").append('<li class="' + disable + ' next-page"><a href="/search/form/2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
}*/
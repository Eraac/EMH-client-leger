var selectedTags = new Array();

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
    // TODO loader
    $.ajax({
        type: 'POST',
       dataType: 'json',
        url: url,
        data:{
            idTag : selectedTags,
            formName : textToSearch
        },
        success: function (datas){
            //console.log(datas);

            $("#listForm").html("");

            var response = "";

            if (0 >= datas['forms'].length)
            {
                $("#listForm").html('<tr><td colspan="4">Aucun formulaire</td></tr>');
            }
            else
            {
                for(i in datas['forms'])
                {
                    response += "<tr>";
                    response +=  "<td>" + datas['forms'][i]['name'] + "</td>";
                    response +=  "<td>";

                    for (y in datas['forms'][i]['tags'])
                    {
                        response += '<span class="label label-info">' + datas['forms'][i]['tags'][y]['name'] + '</span>\n';
                    }

                    response += "</td>";
                    response +=  '<td class="text-center"><a href="/app_dev.php/form/' +  datas['forms'][i]['slug'] + '"><span class="glyphicon glyphicon-search"></span></a></td>'; // TODO Changer URL en prod
                    response += "</tr>";
                }
                $("#listForm").append(response);
            }

            makePagination(datas['hasNext']);
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
        searchForm();
    });


    $("#btnFormSearch").click(function() {
        //console.log($("#formName").val())
        searchForm();
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

    $(".pagination").append('<li class="' + disable + ' next-page"><a href="/search/form/2" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>');
}
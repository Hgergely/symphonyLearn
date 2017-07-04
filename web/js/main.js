$(document).ready(function(){

    $("#AddNewToggleController").on("click",function(){

        $("#addNewSection").slideToggle();

    })

    $(".deleteMovie").on("click",function () {

        var refId = $(this).attr("refId")

        $.ajax({
            type: "GET",
            url: "/movie/delete/" + refId,
            success: function (data, dataType) {

                $(".listRow[refId="+refId+"]").slideUp()
                console.log("success")
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
            }

        })
    })

    $(document).on("click",".WeekChange",function(){

        var offset = $(this).attr("offset")
        $.ajax({
            type: "GET",
            url: "/program/programPartial/" + offset,
            success: function (data, dataType) {

                $("#programWrapper").html(data)

            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
            }

        })

    })

    $(document).on("click",".program",function(){

        var refId = $(this).attr("refId")
        $.ajax({
            type: "GET",
            url: "/movie/details/" + refId,
            success: function (data, dataType) {

               $("#myModal").html(data);
               $("#myModal").modal('show');

            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
            }

        })


    })


})
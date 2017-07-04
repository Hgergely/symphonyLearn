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

    $(".program").on("click",function(){

        var refId = $(this).attr("refId")
        $.ajax({
            type: "GET",
            url: "/movie/details/" + refId,
            success: function (data, dataType) {

                $(".listRow[refId="+refId+"]").slideUp()
                console.log("success")
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
            }

        })

        $("#myModal").modal('show');
    })


})
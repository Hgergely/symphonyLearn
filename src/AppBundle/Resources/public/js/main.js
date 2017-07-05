$(document).ready(function(){

    $("#AddNewToggleController").on("click",function(){

        $("#addNewSection").slideToggle();

    })

    $(".deleteMovie").on("click",function () {

        var refId = $(this).attr("refId")

        $("#loaderSpimner").show()
        $.ajax({
            type: "GET",
            url: "/movie/delete/" + refId,
            success: function (data, dataType) {

                $(".listRow[refId="+refId+"]").slideUp()
                console.log("success")
                $("#loaderSpimner").hide()
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
                $("#loaderSpimner").hide()
            }

        })
    })

    $(document).on("click",".WeekChange",function(){

        var offset = $(this).attr("offset")
        $("#loaderSpimner").show()
        $.ajax({
            type: "GET",
            url: "/program/programPartial/" + offset,
            success: function (data, dataType) {

                $("#programWrapper").html(data)
                $("#loaderSpimner").hide()

            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
                $("#loaderSpimner").hide()
            }

        })

    })

    $(document).on("click",".program",function(){

        var refId = $(this).attr("refId")
        $("#loaderSpimner").show()
        $.ajax({
            type: "GET",
            url: "/movie/details/" + refId,
            success: function (data, dataType) {

                $("#myModal").html(data);
                $("#myModal").modal('show');
                $("#loaderSpimner").hide()

            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
                $("#loaderSpimner").hide()
            }

        })


    })

    $('body').on('submit', '#subscribeForm', function (e) {

        e.preventDefault();
        $(".subscription").hide();
        $("#loaderSpimner").show()
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data, dataType) {

                if (data["success"]=="true"){
                    $(".subscription.alert-success").slideDown();
                    $("#form_email_address").hide();
                    $("#loaderSpimner").hide()
                    $(".subscription[id=form_save]").hide();
                }else{
                    $(".subscription.alert-danger").slideDown();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log('Error : ' + errorThrown);
                $("#loaderSpimner").hide()
                $(".subscription.alert-success").slideDown();
            }
        })

    });


})
// HELPER
function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

// EZ KONFIG FÁJLBÓL, VAGY EGYÉB HELYRŐL JÖN, DE SEMMIKÉPPEN SEM HARDKÓDOLVA
const url = "http://localhost/gablini/app/signup.php";
const mailUrl = "http://localhost/gablini/app/mail.php";

$(document).ready(function () {
    const $userName = $("#username");
    const $userEmail = $("#useremail");
    const $subscribeBtn = $("#subscribeBtn");

    // KLIENS OLDALI ELLENŐRZÉS
    const checkDetails = (name, email) => {
        const errors = [];

        if (name === "" || name.length > 30) {
            errors.push("Adjon meg nevet.");
        }
        if (email == "" || !validateEmail(email)) {
            errors.push("Érényes emailt írjon.");
        }
        return errors;
    };

    // FELIRATKOZÁST ILLETVE LEVÉLKÜLDÉST KÖVETŐEN, VISSZAJELZÉS A USERNEK
    const displayFlash = (messages, type) => {
        $("body").find(".message-box").remove();
        const alertType = type ? "alert-success" : "alert-danger";
        const succesType = type ? "success-box" : "danger-box";

        const $msgBox = $("<div>", {
            class: `${alertType} ${succesType} message-box`,
        }).appendTo($("body"));

        let list = $("<ul>");

        $.each(messages, (_, msg) => {
            $("<li>", {
                text: msg,
            }).appendTo(list);
        });
        list.appendTo($msgBox);

        $("<span>", {
            class: "close-message",
            text: "X",
        })
            .on("click", function () {
                $("body").find(".message-box").remove();
            })
            .appendTo($msgBox);
    };

    // MAGA A FELIRATKOZÁS
    const signUp = (e) => {
        e.preventDefault();

        $subscribeBtn.attr("disabled", true);

        const name = $userName.val();
        const email = $userEmail.val();

        const errors = checkDetails(name, email);
        if (errors.length > 0) {
            displayFlash(errors, false);
        } else {
            $.when(
                $.post(url, JSON.stringify({ name, email })).done((data) => {
                    displayFlash(data.messages, data.success);
                    $userEmail.val("");
                    $userName.val("");
                    if (data.success) {
                        $.post(mailUrl, JSON.stringify({ name, email })).done(
                            (data) => {
                                setTimeout(() => {
                                    displayFlash(data.messages, data.success);
                                }, 3000);
                            }
                        );
                    }
                })
            );
        }
        $subscribeBtn.attr("disabled", false);
    };

    $subscribeBtn.click(signUp);
});

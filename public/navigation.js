
function gotoHome() {
    window.location = "/home";
}

function gotoSearch() {
    window.location = "/search";
}

function gotoProfile() {
    window.location = "/profile";
}

function gotoAdmin() {
    window.location = "/admin";
}

function gotoLogin() {
    window.location = "/login";
}

function gotoRegister() {
    window.location = "/register";
}

function gotoLogout() {
    window.location = "/logout";
}

function searchConferences(id) {
    window.location = "/conferences/conference?id=" + id;
}

function searchForPerson(id) {
    window.location = "/person?id=" + id;
}

function makeReservation(id) {
    window.location = "/reserve?id=" + id;
}

function cancelReservation(id) {
    window.location = "reservationCancel?id=" + id;
}

function createConference() {
    window.location = "/conferences/create";
}

function editConferenceLectures(id) {
    window.location = "/conferences/lectures?id=" + id;
}

function createLectures() {
    window.location = "/lectures/create";
}

function editLecture(id) {
    window.location = "/lectures/edit?id=" + id;
}

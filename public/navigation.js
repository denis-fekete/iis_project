
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
    window.location = "/conferences/conference/" + id;
}

function searchForPerson(id) {
    window.location = "/person/" + id;
}

function makeReservation(id) {
    window.location = "/reservations/reserve/" + id;
}

function cancelReservation(id) {
    window.location = "/reservations/cancel/" + id;
}

function createConference() {
    window.location = "/conferences/create";
}

function editConferenceLectures(id) {
    window.location = "/conferences/lectures/" + id;
}

function createLectures() {
    window.location = "/lectures/create";
}

function editLecture(id) {
    window.location = "/lectures/edit/" + id;
}

function showSchedule(id) {
    window.location = "/reservations/schedule/" + id;
}

function editConference(id) {
    window.location = "/conferences/edit/" + id;
}

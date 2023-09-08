<script setup>
import { reactive } from "vue";
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import moment from "moment";

import MyComponent from "./MyComponent.vue";

// SETTING MOMENT IN FRENCH
moment.locale("fr", {
  months: "janvier_février_mars_avril_mai_juin_juillet_août_septembre_octobre_novembre_décembre".split(
    "_"
  ),
  monthsShort: "janv._févr._mars_avr._mai_juin_juil._août_sept._oct._nov._déc.".split(
    "_"
  ),
  monthsParseExact: true,
  weekdays: "dimanche_lundi_mardi_mercredi_jeudi_vendredi_samedi".split("_"),
  weekdaysShort: "dim._lun._mar._mer._jeu._ven._sam.".split("_"),
  weekdaysMin: "Di_Lu_Ma_Me_Je_Ve_Sa".split("_"),
  weekdaysParseExact: true,
  longDateFormat: {
    LT: "HH:mm",
    LTS: "HH:mm:ss",
    L: "DD/MM/YYYY",
    LL: "D MMMM YYYY",
    LLL: "D MMMM YYYY HH:mm",
    LLLL: "dddd D MMMM YYYY HH:mm",
  },
  calendar: {
    sameDay: "[Aujourd’hui à] LT",
    nextDay: "[Demain à] LT",
    nextWeek: "dddd [à] LT",
    lastDay: "[Hier à] LT",
    lastWeek: "dddd [dernier à] LT",
    sameElse: "L",
  },
  relativeTime: {
    future: "dans %s",
    past: "il y a %s",
    s: "quelques secondes",
    m: "une minute",
    mm: "%d minutes",
    h: "une heure",
    hh: "%d heures",
    d: "un jour",
    dd: "%d jours",
    M: "un mois",
    MM: "%d mois",
    y: "un an",
    yy: "%d ans",
  },
  dayOfMonthOrdinalParse: /\d{1,2}(er|e)/,
  ordinal: function (number) {
    return number + (number === 1 ? "er" : "e");
  },
  meridiemParse: /PD|MD/,
  isPM: function (input) {
    return input.charAt(0) === "M";
  },
  // In case the meridiem units are not separated around 12, then implement
  // this function (look at locale/id.js for an example).
  // meridiemHour : function (hour, meridiem) {
  //     return /* 0-23 hour, given meridiem token and hour 1-12 */ ;
  // },
  // meridiem : function (hours, minutes, isLower) {
  //     return hours < 12 ? 'PD' : 'MD';
  // },
  week: {
    dow: 1, // Monday is the first day of the week.
    doy: 4, // Used to determine first week of the year.
  },
});

moment.locale("fr");

// Laravel datas
defineProps({
  info: Array,
  name: String,
  title: String,
  timestamp: String,
  id: Number,
});

//ERRORS FOR INPUTS
const error = reactive({
  date: "",
  time: "",
});
//pour le date range picker
const date = reactive({
  date_begin: null,
  date_end: null,
});

// MODAL
const inputs = reactive({
  title: "ionut",
  date: "",
  time: "",
});

// MODAL UPDATE
const open = ref(false);

// MODAL CREATE
const open2 = ref(false);

// MODAL UPDATE
const item = ref({});

// DateRangePicker
function filter(begin, end) {
  router.get("/dashboard", { begin: begin, end: end });
  console.log(begin, end);
  date.date_begin = begin;
  date.date_end = end;
}

// MODAL UPDATE
function openModal(id, info) {
  info.forEach((element) => {
    if (element.id == id) {
      item.value = Object.create(element);
    }
  });
  open.value = true;
}

function send_update() {
  //verifier si l'utilisateur a bien formater la date et le timer
  let date = inputs.date;
  if (/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/.test(date)) {
    if (moment(date, "MM/DD/YYYY").isValid()) {
      console.log("validé");
    } else {
      error.date = "Invalide date: doesn't exist";
      inputs.date = "";
    }
    console.log("cest bon");
  } else {
    error.date = "Invalide date: invalide format; try(MM/DD/YYYY)";
    inputs.date = "";
  }
  let time = inputs.time;
  if (/^([01]\d|2[0-3]):[0-5]\d:[0-5]\d$/.test(time)) {
    console.log('It"s a valid time');
  } else {
    console.log("Invalid time format");
    error.time = "Invalide Time: invalide format; try(HH:MM:SS)";
    inputs.time = "";
  }

  if (error.date == "" && error.time == "") {
    router.post("/dashboard/store", {
      title: inputs.title,
      date: inputs.date,
      time: inputs.time,
    });
    console.log("envoyé");
    open2.value = false;
    inputs.title = "";
    inputs.date = "";
    inputs.time = "";
    error.date = "";
    error.time = "";
  }
}

// MODAL CREATE (je crois)
function SaveModal2() {
  open2.value = false;
  inputs.title = "";
}
</script>

<template>
  <div class="dashboard" :class="{ blured: open || open2 }">
    <MyComponent @someEvent="filter" />
    <!-- TODO:A supprimer la div c'est pour debugging purpose -->
    <!-- <div>
      <h1>start: {{ date.date_begin }}</h1>
      <h1>end: {{ date.date_end }}</h1>
    </div> -->
    <div class="main-part">
      <button class="button" @click="open2 = true">Create a new event</button>
      <div class="events_container">
        <div class="event" v-for="item in info">
          <div>{{ item.title }}</div>
          <div>{{ moment(item.date, "DD-MM-YYYY").format("LL") }}</div>
          <div>à {{ item.time }}</div>
          <button class="hov" @click="() => openModal(item.id, info)">Modifier</button>
          <Link
            class="hov del"
            href="/dashboard/delete"
            method="delete"
            as="button"
            :data="{ id: item.id }"
            >Delete</Link
          >
        </div>
      </div>
    </div>
  </div>
  <!-- TODO:: MODAL MODIFIER EVENT -->
  <div v-if="open" class="modal">
    <p>Event ID: {{ item.id }}</p>
    Title
    <input v-model="item.title" />

    <div class="btn-container-modal">
      <Link
        class = "button"
        href="/dashboard/update"
        @click="open = false"
        method="post"
        as="button"
        :data="{ title: item.title, id: item.id }"
        >Save</Link
      >
      <button class="close-btn" @click="open = false">Close</button>
    </div>
  </div>

  <!-- TODO:: Modal Create New Event -->
  <div v-if="open2" class="modal">
    <h2 class="modal-title">Create a new event</h2>
    Title:
    <input v-model="inputs.title" />
    Date:
    <input type="text" v-model="inputs.date" required placeholder="MM/DD/YYYY" />
    <p class="error" v-if="error.date">*{{ error.date }}</p>
    Time:
    <input type="text" v-model="inputs.time" placeholder="HH:MM:SS" />
    <p class="error" v-if="error.time">*{{ error.time }}</p>

    <div class="btn-container-modal">
      <button class="button" @click="send_update">Save</button>
      <button
        class="close-btn"
        @click="
          open2 = false;
          inputs.title = '';
          inputs.date = '';
          inputs.time = '';
          error.date = '';
          error.time = '';
        "
      >
        Close
      </button>
    </div>
  </div>
</template>

<style scoped>
.main-part {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: calc(100% - 450px);
}

.dashboard {
  display: flex;
  flex-direction: row;
  gap: 10px;
  width: 100%;
}

.events_container {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}

.event {
  display: flex;
  justify-content: space-between;
  border: 1px solid black;
  margin: 10px;
  padding: 10px;
  width: 100%;
}

.event > div {
  width: 20%;
}

.modal {
  padding: 20px;
  background-color: aliceblue;
  position: fixed;
  z-index: 999;
  top: 20%;
  left: 50%;
  width: 400px;
  height: 400px;
  margin-left: -150px;
  display: flex;
  flex-direction: column;
  border: 3px solid black;
}

.button {
  width: fit-content;
  padding: 10px;
  align-self: end;
  border-radius: 10px;
  background-color: rgb(0, 183, 255);
  font-size: 1.2rem;
  font-weight: 800;
  color: white;
  margin-top: 10px;
}

.hov:hover {
  text-decoration: underline;
  font-weight: 900;
}

.del:hover {
  color: red;
}

.blured {
  filter: blur(2px);
  pointer-events: none;
}

.btn-container-modal {
  display: flex;
  justify-content: space-between;
}

.close-btn {
  width: fit-content;
  padding: 10px;
  align-self: end;
  border-radius: 10px;
  background-color: rgb(238, 154, 58);
  font-size: 1.2rem;
  font-weight: 800;
  color: white;
  margin-top: 10px;
}

.modal-title {
  font-size: 1.3rem;
  font-weight: 900;
  text-align: center;
}

.error {
  color: red;
}
</style>

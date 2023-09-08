<script setup>
import { ref } from "vue";

const date = ref({
  date_begin: null,
  date_end: null,
});

</script>

<template>
  <div class = "dateRangePicker">
      <div class="ranges">
        <label for="date_begin">Date début:</label>
        <input id="date_begin" type="date" v-model="date.date_begin" />
        <label for="date_end">Date fin:</label>
        <input type="date" v-model="date.date_end" />
      </div>
      <button class="validate" @click="$emit('someEvent', date.date_begin, date.date_end)">Validate</button>
  </div>
</template>

<style scoped>
    .dateRangePicker{
        display: flex;
        flex-direction: column;
        width:400px;
        margin-left:10px;
        margin-top:10px;
        padding-right: 20px;
        border-right:2px solid grey;
    }

    .ranges{
        display: flex;
        flex-direction: column;
        gap:10px;
    }

    .validate{
        margin-top:10px;
        padding: 20px;
        background-color: rgb(0, 183, 255);
        width: fit-content;
        border-radius: 10px;
        font-size: 1.2rem;
        font-weight: 800;
        color: white;

    }
</style>

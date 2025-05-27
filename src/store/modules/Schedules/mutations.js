export default {
  setTodayScheduleMuttation(state, payload){
    state.detailsTodaySchedules = payload.schedule
    state.selectDay = payload.date
  }
}



export default {
async searchScheduleAction (context, payload){
 await  axios.get('/search-histories-schedule/'+payload).then(response=>{
    localStorage.setItem('schedulesForDays',JSON.stringify(response.data))
    context.commit('searchScheduleMuttation',{
      schedule: response.data
    })
  }).catch()
},
async trySearchScheduleAction (context){

   const products = localStorage.getItem('schedulesForDays');
    context.commit('searchScheduleMuttation',{
      schedule: JSON.parse(products)
    })

},
async setTodayhistoriesAction (context,payload){
  let day = payload.day
  let month = payload.month+1
  console.log(payload.day)
  if(payload.day < 10){
     day = `0${payload.day}`
  }

  axios.post('/search-histories-schedule',{date:payload.year+'-'+month+'-'+day}).then(response => {


console.log(response.data)
       context.commit('setTodayScheduleMuttation',{
      schedule: response.data,
         date: payload.year+'-'+month+'-'+day
    })
  });
}
}

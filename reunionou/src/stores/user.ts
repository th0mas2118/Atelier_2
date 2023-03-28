import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import router from '../router/index.js'
import axios from 'axios'

export const useUserStore = defineStore(
  'user',
  () => {
    const isConnected = ref(false)
    const member = reactive({
      email: '',
      id:'',
      username:'',
      level:0,
      acces_token:'',
    })

    async function setConnected(log : {email: string, password: string}){
      await fetch('http://api.frontoffice.reunionou:49383/signin',{
        method: 'POST',
        mode:"cors",
        headers: {
          "Authorization": `Basic ${btoa(`${log.email}:${log.password}`)}`
        }
      }).then((response)=>{
        if(response.status!=201){
          console.log(response.status)
          throw new Error("Erreur de connexion")
        }else{
          return response.json()
        }
      })
      .then((response)=>{
        member.email = response.user.usermail
        member.id = response.user.uid
        member.username = response.user.username
        member.level = response.user.userlevel
        member.acces_token= response.token
        isConnected.value = true
      })
    }

    async function disconnect(){
      await fetch('http://api.frontoffice.reunionou:49383/signout',{
        method:'POST',
        mode:"cors",
        headers: {
          "Authorization": `Bearer ${member.acces_token}`
      }}).then((response)=>isConnected.value = false)
    }
  
    return { isConnected, setConnected,disconnect, member }
  }
)

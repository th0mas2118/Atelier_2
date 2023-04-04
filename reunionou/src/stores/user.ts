import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import router from '../router/index.js'
import axios, { type AxiosResponse } from 'axios'

export const useUserStore = defineStore(
  'user',
  () => {
    const isConnected = ref(false)
    const member = reactive({
      email: '',
      id: '',
      firstname: '',
      lastname: '',
      username: '',
      level: 0,
      acces_token: ''
    })

    const parseJwt = (token: string) => {
      try {
        return JSON.parse(atob(token.split('.')[1]))
      } catch (e) {
        return null
      }
    }

    async function setConnected(log: { email: string; password: string }) {
      const response = await axios.post(`${import.meta.env.VITE_API_HOST}/signin`, {}, {
        headers: {
          Authorization: `Basic ${btoa(`${log.email}:${log.password}`)}`
        }
      });

      if (response.status == 200) {
        const decodedToken = parseJwt(response.data.user.acces_token)
        member.email = decodedToken.usermail
        member.id = decodedToken.uid
        member.firstname = decodedToken.firstname
        member.lastname = decodedToken.lastname
        member.username = decodedToken.username
        member.level = decodedToken.lvl
        member.acces_token = response.data.user.acces_token
        isConnected.value = true
        router.push(`/user/${member.id}`)
      }
    }

    async function disconnect() {
      await fetch(`${import.meta.env.VITE_API_HOST}/signout`, {
        method: 'POST',
        mode: 'cors',
        headers: {
          Authorization: `Bearer ${member.acces_token}`
        }
      }).then((response) => {
        isConnected.value = false
        localStorage.clear()
        router.push('/')
      })
    }

    return { isConnected, setConnected, disconnect, member }
  },
  { persist: true }
)

import { useUserStore } from '@/stores/user';
import type { NavigationGuard } from 'vue-router';

export const auth: NavigationGuard = async (to, from, next) => {
    const userStore = useUserStore();

    if (userStore.isConnected) {
        next();
    } else {
        // You can use try/catch to get an id token and set it to your request header
        // ex: try { ... next() } catch { ... next({ name: '/login') }
        next('/login');
    }
};
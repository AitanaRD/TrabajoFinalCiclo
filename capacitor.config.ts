import type { CapacitorConfig } from '@capacitor/cli';
import {env} from './src/env';

const config: CapacitorConfig = {
  appId: 'com.example.app',
  appName: 'ionicloginsignup03',
  webDir: 'public',
  server: {
    url: env.VITE_ANDROID_SERVER_URL,
    cleartext: true
  }
};

export default config;
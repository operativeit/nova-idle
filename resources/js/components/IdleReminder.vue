<template>
  <span v-if="config.debug">{{display}}</span>
  <Modal
    :show="show"
    role="dialog"
    :size="action.modalSize"
    :modal-style="action.modalStyle"
    >
    <div
      class="bg-white dark:bg-gray-800"
      :class="{
        'rounded-lg shadow-lg overflow-hidden space-y-6':
          action.modalStyle === 'window',
        'flex flex-col justify-between h-full':
          action.modalStyle === 'fullscreen',
      }"
    >
      <ModalContent>
          <h4 class="font-bold text-center text-lg my-3">{{ __('novaIdle.noticeTitle') }}</h4>
          <div class="flex items-center justify-center pb-2">
             <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" class="fill-primary-500" viewBox="0 0 24 24">
                <path d="M15.762 8.047l-4.381 4.475-2.215-2.123-1.236 1.239 3.451 3.362 5.619-5.715-1.238-1.238zm-3.762-5.503c2.5 1.805 4.555 2.292 7 2.416v9.575c0 3.042-1.686 3.827-7 7.107-5.309-3.278-7-4.065-7-7.107v-9.575c2.447-.124 4.5-.611 7-2.416zm0-2.544c-3.371 2.866-5.484 3-9 3v11.535c0 4.603 3.203 5.804 9 9.465 5.797-3.661 9-4.862 9-9.465v-11.535c-3.516 0-5.629-.134-9-3z"/>
             </svg>
          </div>
          <div class="text-center text-md">{{ __('novaIdle.noticeMessage') }}</div>
          <div class="flex items-center justify-center">
                <div class="mr-2">
                   <ProgressRing :min="0" :max="100" :value="diffPercent" :text="display" />
                </div>
                <div class="text-lg font-medium">
                   {{  __('novaIdle.remainingTime', {'value': 'display'}) }}
                </div>
          </div>
      </ModalContent>
      <ModalFooter>
        <div class="flex items-center ml-auto">
          <Button
            type="button"
            ref="runButton"
            dusk="confirm-action-button"
            @click="onClose"
            variant="solid"
            :state="action.destructive ? 'danger' : 'default'"
          >
            {{ __('novaIdle.stayConnected') }}
          </Button>

          <CancelButton
            component="button"
            type="button"
            dusk="cancel-action-button"
            class="ml-auto mr-3"
            @click="onLogout"
          >
            {{ __('novaIdle.closeSession') }}
          </CancelButton>
        </div>
      </ModalFooter>
    </div>
  </Modal>
</template>
<script>
import { Localization } from 'laravel-nova'
import { Button } from 'laravel-nova-ui'
import ProgressRing  from './ProgressRing.vue'

export default {
  components: {
    Button,
    ProgressRing
  },
  mixins: [ Localization ],
  name: "IdleReminder",

  emits: ["idle", "remind"],
  data() {
    return {
      display: "",
      timer: undefined,
      start: 0,
      counter: undefined,
      diff: 0,
      minutes: "",
      seconds: "",
      show: true,
      action: {
            modalSize: 'sm',
            modalStyle: 'window',
            destructive: false,
      },
      duration: 30,
      reminders: [ 15 ], 
      wait: 0,
      events: ["mousemove", "keypress"],
    };
  },
  computed: {
    diffPercent() {
        return this.diff * 100 / this.duration;
    }
  },
  setup() {
    const config = Nova.config('novaIdle');
    return { config };
  },

  mounted() {
    this.duration  = this.config.duration || 30;
    this.reminders  = this.config.reminders || [];
   
    if (this.config.enabled) {
      setTimeout(() => {
        this.start = Date.now();
        this.setDisplay();
        this.$nextTick(() => {
          this.setTimer();
          for (let i = this.events.length - 1; i >= 0; i -= 1) {
            window.addEventListener(this.events[i], this.clearTimer);
          }
        });
      }, this.wait * 1000);
    } else {
      console.warn('nova-idle is disabled');
    }
  },
  methods: {
    onClose() {
      this.show = false;
    },  
    async onLogout() {
      let response = await Nova.request().post(Nova.url('/logout'))
    },
    setDisplay() {
      // seconds since start
      this.diff = this.duration - (((Date.now() - this.start) / 1000) | 0);
      if (this.diff < 0 && !this.loop) {
        return;
      }
      this.shouldRemind();

      // bitwise OR to handle parseInt
      const minute = (this.diff / 60) | 0;
      const second = this.diff % 60 | 0;

      this.minutes = `${minute < 10 ? "0" + minute : minute}`;
      this.seconds = `${second < 10 ? "0" + second : second}`;

      this.display = `${this.minutes}:${this.seconds}`;

      if (this.config.debug) {
        console.log('nova-idle:', this.display);
      }
    },
    shouldRemind() {
      if (this.reminders.length > 0) {
        if (this.reminders.includes(this.diff)) {
          this.remind();
        }
      }
    },
    countdown() {
      this.setDisplay();

      if (this.diff <= 0) {
        this.clearTimer(undefined, this.loop);
        this.onLogout();

      }
    },
    idle() {
      if (this.config.debug) {
        console.log('nova-idle@idle event');
      }
      this.$emit("idle");
    },
    remind() {
      if (this.config.debug) {
        console.log('nova-idle@remind event', this.diff);
      }

      this.$emit("remind", this.diff);
      this.show = true;

    },
    setTimer() {
      this.timer = window.setInterval(this.idle, this.duration * 1000);
      this.counter = window.setInterval(this.countdown, 1000);
    },
    clearTimer(event, loop = true) {
      window.clearInterval(this.timer);
      window.clearInterval(this.counter);
      if (loop) {
        this.start = Date.now();
        this.diff = 0;
        this.setDisplay();
        this.setTimer();
      }
    },
  },
  beforeUnmount() {
    clearInterval(this.timer);
    clearInterval(this.counter);
    for (let i = this.events.length - 1; i >= 0; i -= 1) {
      window.removeEventListener(this.events[i], this.clearTimer);
    }
  },
}
</script>
<style>

.fill-primary-500 {
  fill: rgba(var(--colors-primary-500));
}

</style>

<template>
  <svg class="progress-ring" viewBox="-40,-40,80,80">
    <circle class="progress-ring-circle" :r="radius"/>
    <path class="progress-ring-ring" :d="path"/>
    <text class="pt-2 text-md font-bold" dominant-baseline="middle" alignment-baseline="middle" text-anchor="middle">{{ text }}</text>
  </svg>
</template>
<script>

export default {
  props: {
    value: {
      type: Number,
      default: 0,
    },
    min: {
      type: Number,
      default: 0,
    },
    max: {
      type: Number,
      default: 1,
    },
    text: {
      type: null,
      default: '',
    },
  },
  data() {
    return {
      radius: 30,
    }
  },
  computed: {
    theta() {
      const frac = (this.value - this.min) / (this.max - this.min) || 0;
      return frac * 2 * Math.PI;
    },
    path() {
      const large = this.theta > Math.PI;
      return `M0,-${this.radius} A${this.radius},${this.radius},0,${large ? 1 : 0},1,${this.endX},${this.endY}`;
    },
    endX() {
      return Math.cos(this.theta - Math.PI * 0.5) * this.radius;
    },
    endY() {
      return Math.sin(this.theta - Math.PI * 0.5) * this.radius;
    },
  },
}

</script>
<style>
.progress-ring {
  width: 80px;
  height: 80px;
}

.progress-ring-circle {
  stroke: rgba(var(--colors-gray-200));
  stroke-width: 6;
  fill: none;
}

.progress-ring-ring {
  stroke: rgba(var(--colors-primary-500)); 
  stroke-width: 6;
  fill: none;
}

</style>


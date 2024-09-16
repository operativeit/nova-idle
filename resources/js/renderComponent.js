import { createApp } from 'vue'

export default function renderComponent({ el, component, props, context }) {
  let app = createApp(component, props)
  Object.assign(app._context, context) 
  app.mount(el)

  return () => {
    // destroy app/component
    app?.unmount()
    app = undefined
  }
}

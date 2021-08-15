export default function ({ store, redirect }) {
  // Jika user tidak terautentikasi
  if (!store.state.token) {
    return redirect('/')
  }
}
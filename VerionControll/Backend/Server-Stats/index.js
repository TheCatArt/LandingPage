const express = require('express')
const si = require('systeminformation')
const app = express()
const port = process.env.PORT || 3621

app.get('/stats', async (req, res) => {
    const cpu = await si.currentLoad()
    const mem = await si.mem()
    const disk = await si.fsSize()

    res.json({
        cpu: cpu.currentLoad.toFixed(1),
        ram: ((mem.used / mem.total) * 100).toFixed(1),
        storage: ((disk[0].used / disk[0].size) * 100).toFixed(1)
    })
})

app.listen(port)

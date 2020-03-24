/**
 * stages, stages_user y ids son tablas y los datos esta dentro de los archivos
 * js. Se debe identificar cuales de los usuarios de stages_user no ha pasado 
 * por todos los stages. Todos los usuario son identificados con los ids
 */

import stages from './stages'
import stages_user from './stages_user'
import ids from './ids'

var temp_json = {}
ids.forEach(id => {
    stages_user.forEach(registro => {
        if (registro.id == id) {
            if (typeof (temp_json[id]) !== "object") {
                temp_json[id] = []
            }
            temp_json[id].push(registro)
        }
    })
})

var usuarios_con_estaciones_no_vistas = []
ids.forEach(id => {
    stages.forEach((stage, index) => {
        var STAGE_FOUND = false;
        temp_json[id].forEach(registro => {
            if (registro.stage_name == stage) {
                STAGE_FOUND = true
            }
        })
        if (STAGE_FOUND == false) {
            usuarios_con_estaciones_no_vistas.push({ 'user_id': id, 'user_name': temp_json[id].user_name, 'stage': stage })
        }
    })
})

console.log(usuarios_con_estaciones_no_vistas)
console.log(JSON.stringify(usuarios_con_estaciones_no_vistas)) 
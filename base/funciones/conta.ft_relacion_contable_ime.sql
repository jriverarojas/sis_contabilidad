CREATE OR REPLACE FUNCTION "conta"."ft_relacion_contable_ime" (	
				p_administrador integer, p_id_usuario integer, p_tabla character varying, p_transaccion character varying)
RETURNS character varying AS
$BODY$

/**************************************************************************
 SISTEMA:		Sistema de Contabilidad
 FUNCION: 		conta.ft_relacion_contable_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'conta.trelacion_contable'
 AUTOR: 		 (admin)
 FECHA:	        16-05-2013 21:52:14
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_relacion_contable	integer;
			    
BEGIN

    v_nombre_funcion = 'conta.ft_relacion_contable_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'CONTA_RELCON_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-05-2013 21:52:14
	***********************************/

	if(p_transaccion='CONTA_RELCON_INS')then
					
        begin
        	--Sentencia de la insercion
        	insert into conta.trelacion_contable(
			estado_reg,
			id_tipo_relacion_contable,
			id_cuenta,
			id_partida,
			id_gestion,
			id_auxiliar,
			id_centro_costo,
			fecha_reg,
			id_usuario_reg,
			fecha_mod,
			id_usuario_mod,
			id_tabla
          	) values(
			'activo',
			v_parametros.id_tipo_relacion_contable,
			v_parametros.id_cuenta,
			v_parametros.id_partida,
			v_parametros.id_gestion,
			v_parametros.id_auxiliar,
			v_parametros.id_centro_costo,
			now(),
			p_id_usuario,
			null,
			null,
			v_parametros.id_tabla
							
			)RETURNING id_relacion_contable into v_id_relacion_contable;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Relación Contable almacenado(a) con exito (id_relacion_contable'||v_id_relacion_contable||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_relacion_contable',v_id_relacion_contable::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'CONTA_RELCON_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-05-2013 21:52:14
	***********************************/

	elsif(p_transaccion='CONTA_RELCON_MOD')then

		begin
			--Sentencia de la modificacion
			update conta.trelacion_contable set
			id_tipo_relacion_contable = v_parametros.id_tipo_relacion_contable,
			id_cuenta = v_parametros.id_cuenta,
			id_partida = v_parametros.id_partida,
			id_gestion = v_parametros.id_gestion,
			id_auxiliar = v_parametros.id_auxiliar,
			id_centro_costo = v_parametros.id_centro_costo,
			fecha_mod = now(),
			id_usuario_mod = p_id_usuario,
			id_tabla = v_parametros.id_tabla
			where id_relacion_contable=v_parametros.id_relacion_contable;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Relación Contable modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_relacion_contable',v_parametros.id_relacion_contable::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'CONTA_RELCON_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		16-05-2013 21:52:14
	***********************************/

	elsif(p_transaccion='CONTA_RELCON_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from conta.trelacion_contable
            where id_relacion_contable=v_parametros.id_relacion_contable;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Relación Contable eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_relacion_contable',v_parametros.id_relacion_contable::varchar);
              
            --Devuelve la respuesta
            return v_resp;

		end;
         
	else
     
    	raise exception 'Transaccion inexistente: %',p_transaccion;

	end if;

EXCEPTION
				
	WHEN OTHERS THEN
		v_resp='';
		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
		raise exception '%',v_resp;
				        
END;
$BODY$
LANGUAGE 'plpgsql' VOLATILE
COST 100;
ALTER FUNCTION "conta"."ft_relacion_contable_ime"(integer, integer, character varying, character varying) OWNER TO postgres;
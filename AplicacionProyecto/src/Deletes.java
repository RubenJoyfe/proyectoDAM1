import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.SQLException;

import javax.swing.JTextField;

public class Deletes {
	
	private CallableStatement cst;
	private Connection conexion;
	
	public Deletes(Connection cnx) {
		conexion=cnx;
	}
	
	
	public int eliminarUsuario(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarDesbloqueo(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarDesbloqueo(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarUsuarioDesbloqueo(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario_Desbloqueo(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarUsuarioJuego(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario_Juego(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarPuntuacion(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarPuntuacion(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarJuego(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarJuego(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarAjustes(JTextField[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarAjustes(?,?)}");
			cst.setString(1, txtEditables[0].getText());
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
}

import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.SQLException;

public class Deletes {
	
	private CallableStatement cst;
	private Connection conexion;
	
	public Deletes(Connection cnx) {
		conexion=cnx;
	}
	
	
	public int eliminarUsuario(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarDesbloqueo(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarDesbloqueo(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarUsuarioDesbloqueo(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario_Desbloqueo(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarUsuarioJuego(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarUsuario_Juego(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarPuntuacion(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarPuntuacion(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarJuego(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarJuego(?,?)}");
			cst.setString(1, txtEditables[0]);
			cst.registerOutParameter(2, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(2);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int eliminarAjustes(String[] txtEditables){
		try {
			cst = conexion.prepareCall("{call EliminarAjustes(?,?)}");
			cst.setString(1, txtEditables[0]);
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

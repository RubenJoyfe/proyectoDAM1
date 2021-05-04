import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.SQLException;

public class Updates {
	
	private CallableStatement cst;
	private Connection conexion;
	
	public Updates(Connection cnx) {
		conexion=cnx;
	}
	
	
	public int ModificarUsuario(String[] txtEditables){
		try {
			int longT = txtEditables.length;
			cst = conexion.prepareCall("{call ModificarUsuario(?,?,?,?,?,?,?,?,?,?,?)}");
			for (int i = 0; i < longT; i++) {
				if (txtEditables[i].equals("null")) {
					cst.setString(i+1, null);
				} else {
					cst.setString(i+1, txtEditables[i]);
				}
				
			}
			cst.registerOutParameter(longT+1, java.sql.Types.INTEGER);
			cst.execute();
			return cst.getInt(longT+1);
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return 2;
	}
	
	public int ModificarDesbloqueo(String[] txtEditables){
		
		return 2;
	}
	
	public int ModificarUsuarioDesbloqueo(String[] txtEditables){
		return 2;
	}
	
	public int ModificarUsuarioJuego(String[] txtEditables){
		return 2;
	}
	
	public int ModificarPuntuacion(String[] txtEditables){
		return 2;
	}
	
	public int ModificarJuego(String[] txtEditables){
		return 2;
	}
	
	public int ModificarAjustes(String[] txtEditables){
		return 2;
	}
	
}

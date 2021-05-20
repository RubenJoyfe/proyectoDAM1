import java.awt.BorderLayout;
import java.awt.EventQueue;
import java.awt.GridLayout;
import java.sql.*;

import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;
import javax.swing.JPasswordField;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JTextField;
import javax.swing.SwingConstants;
import javax.swing.border.EmptyBorder;
import javax.swing.event.ListSelectionEvent;
import javax.swing.event.ListSelectionListener;
import javax.swing.table.DefaultTableModel;
import javax.swing.JMenuBar;
import javax.swing.JMenu;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import java.awt.event.ActionListener;
import java.awt.event.ActionEvent;
import java.awt.Font;
import javax.swing.border.LineBorder;
import java.awt.Color;
import java.awt.Dimension;

import javax.swing.Box;
import javax.swing.JButton;
import javax.swing.JTextArea;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;

public class Main extends JFrame {
	private static final long serialVersionUID = 4219163702005532108L;
	
	private JPanel contentPane;
	private JPanel panel_opciones;
	private JPanel panel_tabla;
	private JButton btnInsertar;
	private JButton btnModificar;
	private JButton btnEliminar;
	private JLabel lblNombreTabla;
	private JMenu mnMostrar;
	private JMenuBar menuBar;
	private JMenuItem mntmTabla[];
	private JTable tabla;
	private JScrollPane scroll = new JScrollPane();
	private String titulos [];
	private String tipos[];
	private String[] txtEditables;
	private String nombre  = "",  pass = "";
	
	private Connection conexion;
	private Statement comando;
	private Inserts insertacion;
	private Deletes eliminacion;
	private Updates modificacion;
	private Object celdas [][];
	private Editables[] editaciones = new Editables[0];
	private JTextArea txtCustom;
	private JLabel lblClear;

	private JButton btnCustom;

	private JPanel panel_editables;

	private JLabel lblLogOut;
	
	/**
	 * Launch the application.
	 */
	public static void main(String[] args) {
		EventQueue.invokeLater(new Runnable() {
			public void run() {
				try {
					Main frame = new Main();
					frame.setVisible(true);
					frame.setTitle("Base de Datos h15af00");
					frame.setResizable(false);
				} catch (Exception e) {
					e.printStackTrace();
				}
			}
		});
	}

	/**
	 * Create the frame.
	 */
	public Main() {
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		setBounds(100, 100, 848, 585);
		setLocationRelativeTo(null);
		
		contentPane = new JPanel();
		contentPane.setBorder(new EmptyBorder(5, 5, 5, 5));
		setContentPane(contentPane);
		contentPane.setLayout(null);
		
		panel_editables = new JPanel();
		panel_editables.setBounds(10, 523, 619, 0);
		contentPane.add(panel_editables);
		panel_editables.setLayout(null);
		
		panel_tabla = new JPanel();
		panel_tabla.setBounds(10, 11, 619, 432);
		contentPane.add(panel_tabla);
		
		panel_opciones = new JPanel();
		panel_opciones.setBorder(new LineBorder(Color.GRAY));
		panel_opciones.setBounds(638, 11, 184, 502);
		contentPane.add(panel_opciones);
		panel_opciones.setLayout(null);
		
		lblNombreTabla = new JLabel("TABLA");
		lblNombreTabla.setFont(new Font("Tahoma", Font.PLAIN, 20));
		lblNombreTabla.setHorizontalAlignment(SwingConstants.CENTER);
		lblNombreTabla.setBounds(10, 11, 164, 51);
		panel_opciones.add(lblNombreTabla);
		
		btnInsertar = new JButton("Insertar");
		btnInsertar.setFocusPainted(false);
		btnInsertar.addActionListener(new ActionListener() { // -------------------------> Listener Inserts
			public void actionPerformed(ActionEvent e) {
				getEditableString();
				inserts(); 
			}
		});
		btnInsertar.setBounds(10, 434, 164, 57);
		panel_opciones.add(btnInsertar);
		
		btnModificar = new JButton("Modificar");
		btnModificar.setFocusPainted(false);
		btnModificar.addActionListener(new ActionListener() { // ----------------------> Listener Modificar
			public void actionPerformed(ActionEvent e) {
				String msg = "�Est� seguro que quiere modificar este"
						+ " registro de la tabla " + lblNombreTabla.getText() + "?";
				int op = JOptionPane.showConfirmDialog(null, msg,
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE);
				if (op==0) {
					getEditableString();
					modificar();
				}
				
			}
		});
		btnModificar.setBounds(10, 366, 164, 57);
		panel_opciones.add(btnModificar);
		
		btnEliminar = new JButton("Eliminar");
		btnEliminar.setFocusPainted(false);
		btnEliminar.addActionListener(new ActionListener() { // ----------------------> Listener Eliminar
			public void actionPerformed(ActionEvent e) {
				String msg = "�Est� seguro que quiere eliminar este"
						+ " registro de la tabla " + lblNombreTabla.getText() + "?";
				int op = JOptionPane.showConfirmDialog(null, msg,
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE);
				if (op==0) {
					getEditableString();
					eliminar();
				}
				
			}
		});
		btnEliminar.setBounds(10, 298, 164, 57);
		panel_opciones.add(btnEliminar);
		
		txtCustom = new JTextArea();
		txtCustom.setLineWrap(true);
		
		JScrollPane sp = new JScrollPane(txtCustom);
		sp.setBounds(10, 73, 164, 177);
		panel_opciones.add(sp);
		
		btnCustom = new JButton("Ejecutar");
		btnCustom.setFocusPainted(false);
		btnCustom.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				String sql = txtCustom.getText();
				String type;
				if (sql.length()>5) {
					type = sql.replace(" ","").substring(0, 6).toLowerCase();
				} else {
					JOptionPane.showMessageDialog(null, "Script no v�lido", "Error", 0);
					return;
				}
				switch(type) {
					case "select":
					{
						crearVista(txtCustom.getText());
						break;
					}
					case "insert", "update", "delete":
					{
						if (JOptionPane.showConfirmDialog(null, "Ejecutar: "+sql+"?",
			            "Confirmacion", JOptionPane.YES_NO_OPTION, JOptionPane.WARNING_MESSAGE)==0) {
							try {
								int f = comando.executeUpdate(txtCustom.getText());
								crearTabla(lblNombreTabla.getText().toLowerCase());
								JOptionPane.showMessageDialog(null, f==1?f+" Fila afectada":f+" Filas afectadas", "Success", 1);
							} catch (SQLException e2) {
								printSQLException(e2);
							}
						}
						break;
					}
					default :
					{
						JOptionPane.showMessageDialog(null, "Script no v�lido", "Error", 0);
						break;
					}
				}
			}
		});
		btnCustom.setBounds(10, 248, 82, 23);
		panel_opciones.add(btnCustom);
		
		JButton btnLimpiar = new JButton("Borrar");
		btnLimpiar.addActionListener(new ActionListener() {
			public void actionPerformed(ActionEvent e) {
				if (JOptionPane.showConfirmDialog(btnLimpiar,"�Seguro que desea borrar el texto?","Borrar texto" ,0)==0) {
					txtCustom.setText(null);
				}
			}
		});
		btnLimpiar.setFocusPainted(false);
		btnLimpiar.setBounds(91, 248, 82, 23);
		panel_opciones.add(btnLimpiar);
		
		do {
			login();
//			nombre = "root";
//			pass = "";
		}while (!conexionMysql("localhost", "h15af00", nombre, pass));
		startMenu();
		crearTabla("vac�a");
		insertacion = new Inserts(conexion);
		eliminacion = new Deletes(conexion);
		modificacion = new Updates(conexion);
	}
	
	//********************************* Operaciones MYSQL *****************************************
	public void inserts() {
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = insertacion.insertarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = insertacion.insertarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = insertacion.insertarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = insertacion.insertarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = insertacion.insertarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = insertacion.insertarJuego(txtEditables);		
			break;
			
		case "ajustes":
			resultado = insertacion.insertarAjustes(txtEditables);	
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	public void eliminar(){
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = eliminacion.eliminarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = eliminacion.eliminarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = eliminacion.eliminarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = eliminacion.eliminarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = eliminacion.eliminarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = eliminacion.eliminarJuego(txtEditables);
			break;
			
		case "ajustes":
			resultado = eliminacion.eliminarAjustes(txtEditables);
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	public void modificar(){
		int resultado=1;
		String acTab=lblNombreTabla.getText().toLowerCase();
		
		switch (acTab) {
		case "usuario":
			resultado = modificacion.ModificarUsuario(txtEditables);
			break;
			
		case "desbloqueo":
			resultado = modificacion.ModificarDesbloqueo(txtEditables);
			break;
			
		case "usuario_desbloqueo":
			resultado = modificacion.ModificarUsuarioDesbloqueo(txtEditables);
			break;
			
		case "usuario_juego":
			resultado = modificacion.ModificarUsuarioJuego(txtEditables);
			break;
			
		case "puntuacion":
			resultado = modificacion.ModificarPuntuacion(txtEditables);
			break;
			
		case "juego":
			resultado = modificacion.ModificarJuego(txtEditables);
			break;
			
		case "ajustes":
			resultado = modificacion.ModificarAjustes(txtEditables);
			break;
			
		default:
			JOptionPane.showMessageDialog(null, "Tabla no especificada", "Error", JOptionPane.ERROR_MESSAGE, null);
			break;
		}
		if (resultado==0) {
			crearTabla(acTab);
			JOptionPane.showMessageDialog(null, "Todo correcto", "Success", JOptionPane.INFORMATION_MESSAGE, null);
		} else {
			JOptionPane.showMessageDialog(null, "Error "+resultado, "Error", JOptionPane.ERROR_MESSAGE, null);
		}
	}
	
	public static void printSQLException(SQLException ex) {
	    for (Throwable e : ex) {
	        if (e instanceof SQLException) {
                JOptionPane.showMessageDialog(null, e.getMessage(),
                		"Error Code: " + ((SQLException)e).getErrorCode(), 0);
                Throwable t = ex.getCause();
                while(t != null) {
                    System.out.println("Cause: " + t);
                    t = t.getCause();
	            }
	        }
	    }
	}
	
	
	//********************************* Fin MYSQL *************************************************
	
	public void startMenu() {
		menuBar = new JMenuBar();
		setJMenuBar(menuBar);
//		mnMostrar.setPreferredSize(new Dimension(100, mnMostrar.getPreferredSize().height));
		mnMostrar = new JMenu("   Tablas   ");
		mnMostrar.setBackground(Color.LIGHT_GRAY);
		mnMostrar.setForeground(Color.BLACK);
		mnMostrar.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		menuBar.add(mnMostrar);
		mnMostrar.addMouseListener(new MouseAdapter() {
			public void mouseEntered(MouseEvent e) {
				mnMostrar.setOpaque(true);
				mnMostrar.repaint();
			}
			public void mouseExited(MouseEvent e) {
				mnMostrar.setOpaque(false);
				mnMostrar.repaint();
			}
		});
		
		lblClear = new JLabel("   Limpiar Campos   ") {
			private static final long serialVersionUID = 1L;
			// Maximum size should be larger than what the JMenuBar will ever be.
		    @Override
		    public Dimension getMaximumSize() {
		        return new Dimension((int)lblClear.getPreferredSize().getWidth(), 1000);
		    }
		};
		lblClear.setBackground(Color.lightGray);
		lblClear.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		
		lblClear.addMouseListener(new MouseAdapter() {
			public void mousePressed(MouseEvent e) {
				for (int i = 0; i < editaciones.length; i++) {
					editaciones[i].setText(null);
				}
				contentPane.requestFocus();
			}
			public void mouseEntered(MouseEvent e) {
				lblClear.setOpaque(true);
				lblClear.repaint();
			}
			public void mouseExited(MouseEvent e) {
				lblClear.setOpaque(false);
				lblClear.repaint();
			}
		});
		menuBar.add(lblClear);
		
		menuBar.add(Box.createHorizontalGlue());
		lblLogOut = new JLabel("   Cerrar Sesion   ") {
			private static final long serialVersionUID = 1L;
			// Maximum size should be larger than what the JMenuBar will ever be.
		    @Override
		    public Dimension getMaximumSize() {
		        return new Dimension((int)lblClear.getPreferredSize().getWidth(), 1000);
		    }
		};
		lblLogOut.setBackground(Color.lightGray);
		lblLogOut.setFont(new Font("Segoe UI", Font.PLAIN, 12));
		
		lblLogOut.addMouseListener(new MouseAdapter() {
			public void mousePressed(MouseEvent e) {
		        dispose();
				try {
					Main frame = new Main();
					frame.setVisible(true);
					frame.setTitle("Base de Datos h15af00");
					frame.setResizable(false);
				} catch (Exception e2) {
					e2.printStackTrace();
				}
			}
			public void mouseEntered(MouseEvent e) {
				lblLogOut.setOpaque(true);
				lblLogOut.repaint();
			}
			public void mouseExited(MouseEvent e) {
				lblLogOut.setOpaque(false);
				lblLogOut.repaint();
			}
		});
		menuBar.add(lblLogOut);
		
		String sql = "SELECT table_name AS nombre FROM information_schema.tables"
				+ " WHERE table_schema = 'h15af00' ORDER BY nombre ASC";
		try {
			int rows=0;
			comando = conexion.createStatement();
			ResultSet rs = comando.executeQuery(sql);
			
			while (rs.next()) {rows++;} //contar el numero de filas que devuelve
			mntmTabla = new JMenuItem [rows];
			rs = comando.executeQuery(sql);
			
			rows=0;
			while (rs.next()) {
				String nombreOp = rs.getString("nombre");
				String nombreMay = rs.getString("nombre").substring(0, 1).toUpperCase()+ rs.getString("nombre").substring(1);
				mntmTabla[rows] = new JMenuItem(nombreMay);
				mnMostrar.add(mntmTabla[rows]);
				mntmTabla[rows].addActionListener(new ActionListener() {
					public void actionPerformed(ActionEvent e) {
						crearTabla(nombreOp);
						lblNombreTabla.setText(nombreMay);
						enableButtons(true);
					}
				});
				rows++;
			}
			
		} catch (SQLException e) {
			e.printStackTrace();
		}
			
	
	}

	public void startEditables(String columnas []) {
		deleteEditables();
		editaciones = new Editables[columnas.length];
		for (int i = 0; i < columnas.length; i++) {
			editaciones[i] = new Editables(columnas[i], tipos[i], i,  panel_editables);
		}
	}
	
	public void deleteEditables() {
		for (int i = 0; i < editaciones.length; i++) {
			editaciones[i].remove();
		}
		contentPane.repaint();
	}
	
	public void getEditableString() {
		txtEditables = new String[editaciones.length];
		for (int i = 0; i < editaciones.length; i++) {
			txtEditables[i] = editaciones[i].getText();
		}
	}
	
	public boolean conexionMysql(String ip, String bbdd, String usuario, String pass) {
		try {
			conexion = DriverManager.getConnection(
			        "jdbc:mysql://" + ip + "/" + bbdd + "?serverTimezone=UTC", usuario, pass);//jdbc:mysql://<ip>:<puerto>/<nombreDB>,"user","pass"
			return true;
		} catch (SQLException e) {
			printSQLException(e);
		}
		return false;
	}
	
	public void login() {
		JPanel panel = new JPanel(new BorderLayout(5, 5));
		

			JPanel label = new JPanel(new GridLayout(0, 1, 2, 2));
		    label.add(new JLabel("Usuario", SwingConstants.RIGHT));
		    label.add(new JLabel("Contrase�a", SwingConstants.RIGHT));
		    panel.add(label, BorderLayout.WEST);
		    
		    JPanel controls = new JPanel(new GridLayout(0, 1, 2, 2));
		    JTextField username = new JTextField();
		    controls.add(username);
		    JPasswordField password = new JPasswordField();
		    controls.add(password);
		    panel.add(controls, BorderLayout.CENTER);
		    
		    int op = JOptionPane.showConfirmDialog(this, panel, "login", JOptionPane.OK_CANCEL_OPTION);
		    if (op==2||op==-1) {
		    	System.exit(0);
			}
		    
		    nombre = username.getText();
		    pass = new String(password.getPassword());		   
	}
	
	public void crearVista(String str) {
		try {
			comando.executeUpdate("DROP VIEW IF EXISTS custom;");
			comando.executeUpdate("CREATE VIEW custom AS "+ str +";");
			crearTabla("custom");
			enableButtons(false);
		} 
		catch (SQLException e) {
			printSQLException(e);
		}
		try {
			comando.executeUpdate("DROP VIEW IF EXISTS custom;");
		} catch (SQLException e) {
			printSQLException(e);
		}
	}
	
	private void enableButtons(boolean b) {
		btnEliminar.setEnabled(b);
		btnInsertar.setEnabled(b);
		btnModificar.setEnabled(b);
	}

	//*******************************Crear Tabla****************************
	@SuppressWarnings({ "serial", "rawtypes" })
	public void crearTabla(String tb) {
		contentPane.requestFocus();
		if (tb.equals("vac�a")) {
			tabla = new JTable();
			panel_tabla.setLayout(null);
			panel_tabla.setBounds(10, 11, 619, 502);
			scroll.setViewportView(tabla);
			scroll.setBounds(0, 0, 618, 502);
			panel_editables.setBounds(10, 523, 619, 0);
		    panel_tabla.add(scroll);
		} else {
			int nCol=0, nRow=0;
			ResultSet rs;
			String sql = "SELECT * FROM " + tb +";";
			String sql2= "SELECT COLUMN_NAME as nombreColumna FROM information_schema.COLUMNS WHERE "
					+ "TABLE_SCHEMA  LIKE 'h15af00' AND TABLE_NAME = '"+tb+"'";
			String sql3 = "SELECT COLUMN_TYPE as tipos FROM information_schema.COLUMNS "
					+ "WHERE TABLE_NAME = '"+tb+"'";
			
			try {
				int cont=0;
				
				rs = comando.executeQuery(sql2);
				while (rs.next()) {nCol++;} //cuenta el numero de columnas
				titulos = new String [nCol]; //nCol asigna su tamano
				tipos = new String[nCol];
				rs = comando.executeQuery(sql2);
				while (rs.next()) { 
					titulos[cont]=rs.getString("nombreColumna");
					cont++;
				}
				cont = 0;
				rs = comando.executeQuery(sql3);
				while (rs.next()) { 
					tipos[cont]=rs.getString("tipos");
					cont++;
				}
				startEditables(titulos);
				ResultSet rs2 = comando.executeQuery(sql);
				while (rs2.next()) {nRow++;}
				celdas=new Object[nRow][nCol];
				cont=0;
				int cont2=0;
				rs2 = comando.executeQuery(sql);
				while (rs2.next()) {
					while (cont2<nCol) {
						try {	// Guarda Entero si es un entero --> Permite la ordenacion de las columnas
							celdas[cont][cont2]=Integer.parseInt(rs2.getString(titulos[cont2]));
						} catch (Exception e) {
							celdas[cont][cont2]=rs2.getString(titulos[cont2]);
						}
						cont2++;
					}
					cont++;
					cont2=0;
				}
				
				// Establece el tipo de las columnas de enteros
				Class[] columnasTipo = new Class[tipos.length];
				for (int i = 0; i < columnasTipo.length; i++) {
					if (tipos[i].contains("int")) {
						columnasTipo[i]=Integer.class;
					} else {
						columnasTipo[i]=Object.class;
					}
				}
				
				// Inicializa la tabla con el modelo establecido
				tabla.setModel(new DefaultTableModel(celdas, titulos){
					public boolean isCellEditable(int row, int column) {
						return false;
					}
					Class[] columnTypes = columnasTipo;
					@SuppressWarnings("unchecked")
					public Class getColumnClass(int columnIndex) {
						return columnTypes[columnIndex];
					}
				});				
				//tabla=new JTable(celdas, titulos);
			} 
			catch (SQLException e) {
				e.printStackTrace();
			}
			int move = 30*(1+(nCol-1)/6);
			panel_tabla.setBounds(10, 11, 619, 502-move);
			scroll.setBounds(0, 0, 619, 502-move);
			panel_editables.setBounds(10, 523-move, 620, move);
		}
		tabla.setName(tb);
		panel_tabla.setLayout(null);
		scroll.setViewportView(tabla);
	    panel_tabla.add(scroll);
	    tabla.setCellSelectionEnabled(true);
	    tabla.setAutoCreateRowSorter(true);
		tabla.getSelectionModel().addListSelectionListener(new ListSelectionListener(){
	        public void valueChanged(ListSelectionEvent event) {
	        	if(event.getValueIsAdjusting()) {
	        		int n = tabla.getSelectedRow();
	        		if (n>=0) {
	        			int fila = tabla.convertRowIndexToModel(n);
						for (int i = 0; i < editaciones.length; i++) {
							editaciones[i].setText(""+tabla.getModel().getValueAt(fila, i));
						}
					}
	        	}
	        }
	    });
	}
}

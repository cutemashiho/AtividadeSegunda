Para executar faça o download em zip, clique em extrair tudo e por fim clique duas vezes em "ABRIR_PDV.bat".

Patterns usados no projeto de PDV

Singleton (models/Conexao.php): Abre a conexão com o banco SQLite na primeira chamada e reutiliza a mesma conexão até terminar aquela requisição.

DAO (models/ClienteDAO.php e outros): Separa o código do banco de dados. Por exemplo, quando o sistema precisa cadastrar um cliente, o controlador chama o método inserir() e o ClienteDAO executa o SQL.

Front Controller (public/index.php): É o ponto de entrada do sistema. Ele verifica qual tela foi pedida e chama o controlador certo. Por exemplo, ao abrir a tela de clientes, ele chama controllers/clientes.php.

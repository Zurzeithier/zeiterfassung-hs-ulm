package database;

import errors.DBError;
import java.io.File;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/**
 *
 * @author manuel
 */
public class ConnectionDataProvider
{

    private static String m_Adress = "";
    private static String m_Username = "";
    private static String m_Password = "";
    private static boolean m_Initialized = false;

    public static String getAdress()
    {
        if (!m_Initialized)
        {
            initialize();
        }

        return m_Adress;
    }

    public static String getPassword()
    {
        if (!m_Initialized)
        {
            initialize();
        }

        return m_Password;
    }

    public static String getUsername()
    {
        if (!m_Initialized)
        {
            initialize();
        }

        return m_Username;
    }

    private static void initialize()
    {
        String configFile = "config/dbConnect.xml";
        
        m_Adress = "www.illertech.net;databaseName=Zeiterfassung";
        m_Username = "sa";
        m_Password = "odysee2001";
        /*
        try
        {
            File domFile = new File(configFile);
            if (domFile.exists())
            {
                DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
                DocumentBuilder builder = factory.newDocumentBuilder();
                Document document = builder.parse(domFile);
                Node rootNode = document.getDocumentElement();

                NodeList childs = rootNode.getChildNodes();
                Node nodeChild = null;

                for (int j = 0; j < childs.getLength(); j++)
                {
                    nodeChild = childs.item(j);
                    if (nodeChild != null)
                    {
                        if ("url".equals(nodeChild.getNodeName()))
                        {
                            m_Adress = nodeChild.getTextContent();
                        }
                        else if ("user".equals(nodeChild.getNodeName()))
                        {
                            m_Username = nodeChild.getTextContent();
                        }
                        else if ("pwd".equals(nodeChild.getNodeName()))
                        {
                            m_Username = nodeChild.getTextContent();
                        }
                    }
                }
                
                m_Initialized = true;
            }
            else 
            {
                throw new DBError(configFile + " file does not exist!");
            }
        }
        catch (Throwable ex)
        {
            throw new DBError(ex);
        }*/
    }

}

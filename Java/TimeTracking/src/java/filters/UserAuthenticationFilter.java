/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package filters;

import beans.UserBean;
import java.io.IOException;
import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import timetracking.SessionBean1;

/**
 *
 * @author manuel
 */
public class UserAuthenticationFilter implements Filter
{

    private final static String FILTER_APPLIED = "_security_filter_applied";

    public void init(FilterConfig config) throws ServletException
    {
    }

    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain) throws IOException, ServletException
    {
        HttpServletRequest hreq = (HttpServletRequest) request;
        HttpServletResponse hres = (HttpServletResponse) response;
        HttpSession session = hreq.getSession();

        String checkforloginpage = hreq.getPathTranslated();

        //dont filter login.jsp because otherwise an endless loop.
        //& only filter .jsp otherwise it will filter all images etc as well.
        if ((request.getAttribute(FILTER_APPLIED) == null) && (!checkforloginpage.endsWith("Login.jsp")) && (checkforloginpage.endsWith(".jsp")))
        {
            request.setAttribute(FILTER_APPLIED, Boolean.TRUE);

            UserBean user = null;
            if (((SessionBean1) session.getAttribute("SessionBean1")) != null)
            {
                user = ((SessionBean1) session.getAttribute("SessionBean1")).getUser();
            }
            
            if (user == null)
            {
                hres.sendRedirect("/TimeTracking"); // send user to default page
                return;
            }

        }

        //deliver request to next filter 
        chain.doFilter(request, response);
    }

    public void destroy()
    {
    }

}

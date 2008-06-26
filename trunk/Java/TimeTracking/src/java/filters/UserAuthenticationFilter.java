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
 * Handles the user authentication
 * @author manuel, steffen
 */
public class UserAuthenticationFilter implements Filter
{

    private final static String FILTER_APPLIED = "_authentication_filter_applied";

    /**
     * 
     * @param config
     * @throws javax.servlet.ServletException
     */
    public void init(FilterConfig config) throws ServletException
    {
    }

    /**
     * Proves if the user is logged in
     * @param request
     * @param response
     * @param chain
     * @throws java.io.IOException
     * @throws javax.servlet.ServletException
     */
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain) throws IOException, ServletException
    {
        HttpServletRequest hreq = (HttpServletRequest) request;
        HttpServletResponse hres = (HttpServletResponse) response;
        HttpSession session = hreq.getSession();

        if (request.getAttribute(FILTER_APPLIED) == null)
        {
            request.setAttribute(FILTER_APPLIED, Boolean.TRUE);

            UserBean user = null;
            if (((SessionBean1) session.getAttribute("SessionBean1")) != null)
            {
                user = ((SessionBean1) session.getAttribute("SessionBean1")).getUser();
            }
            
            
            if (user == null)   // checkes if user is loged in
            {
                hres.sendRedirect("/TimeTracking");     // send user to default page
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
